<?php

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use App\Support\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

beforeEach(function () {
    config([
        'services.paystation.environment' => 'sandbox',
        'services.paystation.merchant_id' => 'test-merchant',
        'services.paystation.password' => 'test-password',
        'services.paystation.currency' => 'BDT',
        'services.paystation.sandbox_url' => 'https://sandbox.paystation.com.bd',
        'services.paystation.live_url' => 'https://api.paystation.com.bd',
    ]);

    Http::preventStrayRequests();
});

function createPayableCourse(array $overrides = []): Course
{
    return Course::create(array_merge([
        'title' => 'Checkout Course',
        'slug' => 'checkout-course',
        'short_description' => 'A course for checkout tests.',
        'price' => 5900,
        'discounted_price' => 4700,
        'currency' => 'BDT',
        'status' => Course::STATUS_PUBLISHED,
        'published_at' => now(),
    ], $overrides));
}

function createPayableService(array $overrides = []): Service
{
    return Service::create(array_merge([
        'name' => 'Checkout Service',
        'slug' => 'checkout-service',
        'short_description' => 'A service for checkout tests.',
        'starting_price' => 14900,
        'status' => 'active',
    ], $overrides));
}

/**
 * @return array{0: Order, 1: Payment}
 */
function createPendingPaystationOrder(User $student, Course $course, int $amount = 4700, string $invoice = 'PS-TEST-100'): array
{
    $order = Order::create([
        'user_id' => $student->id,
        'order_number' => 'ND-TEST-100',
        'subtotal' => $amount,
        'discount_total' => 0,
        'total' => $amount,
        'currency' => 'BDT',
        'status' => 'pending',
    ]);

    $order->items()->create([
        'course_id' => $course->id,
        'item_type' => 'course',
        'course_title' => $course->title,
        'price' => $amount,
        'discount' => 0,
        'total' => $amount,
    ]);

    $payment = $order->payments()->create([
        'provider' => 'paystation',
        'transaction_ref' => $invoice,
        'amount' => $amount,
        'currency' => 'BDT',
        'status' => 'pending',
    ]);

    return [$order, $payment];
}

it('requires authentication before checkout', function () {
    $course = createPayableCourse();

    $this->get(route('checkout.course.show', $course->slug))
        ->assertRedirect(route('login'));
});

it('shows the course checkout total in BDT', function () {
    $student = User::factory()->student()->create();
    $course = createPayableCourse();

    $this->actingAs($student)
        ->get(route('checkout.course.show', $course->slug))
        ->assertOk()
        ->assertSee($course->title)
        ->assertSee(Currency::format(4700, 'BDT', 'BDT'))
        ->assertSee(__('app.checkout_pay'));
});

it('redirects enrolled students away from course checkout', function () {
    $student = User::factory()->student()->create();
    $course = createPayableCourse();

    Enrollment::create([
        'user_id' => $student->id,
        'course_id' => $course->id,
        'status' => 'active',
        'lifetime_access' => true,
        'enrolled_at' => now(),
    ]);

    $this->actingAs($student)
        ->get(route('checkout.course.show', $course->slug))
        ->assertRedirect(route('dashboard'))
        ->assertSessionHas('status', __('app.checkout_already_enrolled'));
});

it('enrolls a student immediately in a free course', function () {
    $student = User::factory()->student()->create();
    $course = createPayableCourse([
        'price' => 0,
        'discounted_price' => null,
    ]);

    $this->actingAs($student)
        ->post(route('checkout.course.store', $course->slug), ['phone' => '01700000000'])
        ->assertRedirect(route('dashboard'));

    expect(Order::query()->firstOrFail()->status)->toBe('paid')
        ->and(Enrollment::query()->count())->toBe(1)
        ->and($course->fresh()->enrollment_count)->toBe(1);
});

it('initiates a PayStation payment for a course', function () {
    $student = User::factory()->student()->create();
    $course = createPayableCourse();

    Http::fake([
        'sandbox.paystation.com.bd/initiate-payment' => Http::response([
            'status_code' => '200',
            'payment_url' => 'https://sandbox.paystation.com.bd/pay/abc',
        ]),
    ]);

    $this->actingAs($student)
        ->post(route('checkout.course.store', $course->slug), ['phone' => '01700000000'])
        ->assertRedirect('https://sandbox.paystation.com.bd/pay/abc');

    $order = Order::query()->firstOrFail();
    $payment = $order->payments()->firstOrFail();

    expect($order->status)->toBe('pending')
        ->and($order->items()->firstOrFail()->course_id)->toBe($course->id)
        ->and($payment->status)->toBe('pending')
        ->and((float) $payment->amount)->toBe(4700.0)
        ->and($payment->transaction_ref)->toStartWith('PS-');

    Http::assertSent(fn ($request): bool => $request->url() === 'https://sandbox.paystation.com.bd/initiate-payment'
        && $request['invoice_number'] === $payment->transaction_ref
        && $request['payment_amount'] === 4700
        && $request['cust_phone'] === '01700000000');
});

it('initiates a PayStation payment for a service', function () {
    $student = User::factory()->student()->create();
    $service = createPayableService();

    Http::fake([
        'sandbox.paystation.com.bd/initiate-payment' => Http::response([
            'status_code' => '200',
            'payment_url' => 'https://sandbox.paystation.com.bd/pay/service',
        ]),
    ]);

    $this->actingAs($student)
        ->post(route('checkout.service.store', $service->slug), ['phone' => '01700000000'])
        ->assertRedirect('https://sandbox.paystation.com.bd/pay/service');

    $item = Order::query()->firstOrFail()->items()->firstOrFail();

    expect($item->service_id)->toBe($service->id)
        ->and($item->item_type)->toBe('service')
        ->and($item->course_title)->toBe($service->name);
});

it('verifies a successful callback and enrolls the student', function () {
    $student = User::factory()->student()->create();
    $course = createPayableCourse();
    [$order, $payment] = createPendingPaystationOrder($student, $course);

    Http::fake([
        'sandbox.paystation.com.bd/v2/transaction-status' => Http::response([
            'status_code' => '200',
            'data' => [
                'invoice_number' => $payment->transaction_ref,
                'trx_id' => 'TRX-123',
                'trx_status' => 'Success',
                'request_amount' => '4700',
            ],
        ]),
    ]);

    $this->actingAs($student)
        ->get(route('payments.paystation.callback', [
            'status' => 'Successful',
            'invoice_number' => $payment->transaction_ref,
            'trx_id' => 'TRX-123',
        ]))
        ->assertRedirect(route('checkout.result', $order));

    expect($order->fresh()->status)->toBe('paid')
        ->and($payment->fresh()->status)->toBe('succeeded')
        ->and($payment->fresh()->gateway_reference)->toBe('TRX-123')
        ->and($course->fresh()->enrollment_count)->toBe(1);

    $this->assertDatabaseHas('enrollments', [
        'user_id' => $student->id,
        'course_id' => $course->id,
        'order_id' => $order->id,
    ]);
});

it('does not complete a payment when the verified amount does not match', function () {
    $student = User::factory()->student()->create();
    $course = createPayableCourse();
    [$order, $payment] = createPendingPaystationOrder($student, $course);

    Http::fake([
        'sandbox.paystation.com.bd/v2/transaction-status' => Http::response([
            'status_code' => '200',
            'data' => [
                'invoice_number' => $payment->transaction_ref,
                'trx_id' => 'TRX-123',
                'trx_status' => 'Success',
                'request_amount' => '1',
            ],
        ]),
    ]);

    $this->actingAs($student)
        ->get(route('payments.paystation.callback', [
            'status' => 'Successful',
            'invoice_number' => $payment->transaction_ref,
            'trx_id' => 'TRX-123',
        ]))
        ->assertRedirect(route('checkout.result', $order))
        ->assertSessionHas('status', __('app.checkout_verification_failed'));

    expect($order->fresh()->status)->toBe('pending')
        ->and($payment->fresh()->status)->toBe('pending')
        ->and(Enrollment::query()->count())->toBe(0);
});

it('completes an IPN once and ignores duplicate notifications', function () {
    $student = User::factory()->student()->create();
    $course = createPayableCourse();
    [$order, $payment] = createPendingPaystationOrder($student, $course);

    Http::fake([
        'sandbox.paystation.com.bd/v2/transaction-status' => Http::response([
            'status_code' => '200',
            'data' => [
                'invoice_number' => $payment->transaction_ref,
                'trx_id' => 'TRX-IPN-1',
                'trx_status' => 'Success',
                'request_amount' => '4700',
            ],
        ]),
    ]);

    $payload = [
        'invoice_number' => $payment->transaction_ref,
        'trx_id' => 'TRX-IPN-1',
        'trx_status' => 'Success',
    ];

    $this->post(route('payments.paystation.ipn'), $payload)
        ->assertOk()
        ->assertJson(['message' => 'Payment verified.']);

    $this->post(route('payments.paystation.ipn'), $payload)
        ->assertOk();

    expect($order->fresh()->status)->toBe('paid')
        ->and($payment->fresh()->status)->toBe('succeeded')
        ->and(Enrollment::query()->count())->toBe(1)
        ->and($course->fresh()->enrollment_count)->toBe(1);
});
