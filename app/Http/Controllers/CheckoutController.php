<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Service;
use App\Services\PaystationPaymentService;
use App\Support\Currency;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class CheckoutController extends Controller
{
    public function showCourse(Course $course): View|RedirectResponse
    {
        abort_unless($course->status === Course::STATUS_PUBLISHED, 404);

        if ($this->alreadyEnrolled($course)) {
            return redirect()->route('dashboard')->with('status', __('app.checkout_already_enrolled'));
        }

        $subtotal = $this->courseSubtotal($course);
        $total = $this->courseTotal($course);

        return view('checkout.show', [
            'itemType' => 'course',
            'title' => $course->title,
            'description' => $course->short_description,
            'subtotal' => $subtotal,
            'discount' => max(0, $subtotal - $total),
            'total' => $total,
            'checkoutUrl' => route('checkout.course.store', $course->slug),
        ]);
    }

    public function storeCourse(Request $request, Course $course, PaystationPaymentService $payments): RedirectResponse
    {
        abort_unless($course->status === Course::STATUS_PUBLISHED, 404);

        if ($this->alreadyEnrolled($course)) {
            return redirect()->route('dashboard')->with('status', __('app.checkout_already_enrolled'));
        }

        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $this->rememberPhone($request, $validated['phone']);

        $subtotal = $this->courseSubtotal($course);
        $total = $this->courseTotal($course);

        if ($total <= 0) {
            $this->createFreeCourseOrder($request, $course, $subtotal);

            return redirect()->route('dashboard')->with('status', __('app.checkout_success'));
        }

        [$order, $payment] = $this->createOrder($request, [
            'course' => $course,
            'title' => $course->title,
            'subtotal' => $subtotal,
            'total' => $total,
        ]);

        return $this->redirectToPaystation($payments, $order, $payment, $validated['phone']);
    }

    public function showService(Service $service): View
    {
        abort_unless(in_array($service->status, ['active', 'published'], true), 404);

        $total = round((float) $service->starting_price, 2);

        abort_if($total <= 0, 404);

        return view('checkout.show', [
            'itemType' => 'service',
            'title' => $service->name,
            'description' => $service->short_description,
            'subtotal' => $total,
            'discount' => 0.0,
            'total' => $total,
            'checkoutUrl' => route('checkout.service.store', $service->slug),
        ]);
    }

    public function storeService(Request $request, Service $service, PaystationPaymentService $payments): RedirectResponse
    {
        abort_unless(in_array($service->status, ['active', 'published'], true), 404);

        $total = round((float) $service->starting_price, 2);

        abort_if($total <= 0, 404);

        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $this->rememberPhone($request, $validated['phone']);

        [$order, $payment] = $this->createOrder($request, [
            'service' => $service,
            'title' => $service->name,
            'subtotal' => $total,
            'total' => $total,
        ]);

        return $this->redirectToPaystation($payments, $order, $payment, $validated['phone']);
    }

    public function result(Order $order): View
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load(['items.course', 'items.service', 'payments']);

        return view('checkout.result', [
            'order' => $order,
            'payment' => $order->payments->sortByDesc('id')->first(),
        ]);
    }

    private function alreadyEnrolled(Course $course): bool
    {
        return Enrollment::query()
            ->where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->exists();
    }

    private function rememberPhone(Request $request, string $phone): void
    {
        if (blank($request->user()->phone)) {
            $request->user()->update(['phone' => $phone]);
        }
    }

    /**
     * @param  array<string, mixed>  $item
     * @return array{0: Order, 1: Payment}
     */
    private function createOrder(Request $request, array $item): array
    {
        return DB::transaction(function () use ($request, $item): array {
            $course = $item['course'] ?? null;
            $service = $item['service'] ?? null;
            $subtotal = (float) $item['subtotal'];
            $total = (float) $item['total'];

            $order = Order::create([
                'user_id' => $request->user()->id,
                'order_number' => $this->orderNumber(),
                'subtotal' => $subtotal,
                'discount_total' => max(0, $subtotal - $total),
                'total' => $total,
                'currency' => 'BDT',
                'status' => 'pending',
            ]);

            $order->items()->create([
                'course_id' => $course?->id,
                'service_id' => $service?->id,
                'item_type' => $course ? 'course' : 'service',
                'course_title' => (string) $item['title'],
                'price' => $subtotal,
                'discount' => max(0, $subtotal - $total),
                'total' => $total,
            ]);

            $payment = $order->payments()->create([
                'provider' => 'paystation',
                'transaction_ref' => $this->invoiceNumber(),
                'amount' => $total,
                'currency' => 'BDT',
                'status' => 'pending',
            ]);

            return [$order->load('items', 'user'), $payment];
        });
    }

    private function createFreeCourseOrder(Request $request, Course $course, float $subtotal): void
    {
        DB::transaction(function () use ($request, $course, $subtotal): void {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'order_number' => $this->orderNumber(),
                'subtotal' => $subtotal,
                'discount_total' => $subtotal,
                'total' => 0,
                'currency' => 'BDT',
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            $order->items()->create([
                'course_id' => $course->id,
                'item_type' => 'course',
                'course_title' => $course->title,
                'price' => $subtotal,
                'discount' => $subtotal,
                'total' => 0,
            ]);

            $enrollment = Enrollment::firstOrCreate(
                [
                    'user_id' => $request->user()->id,
                    'course_id' => $course->id,
                ],
                [
                    'order_id' => $order->id,
                    'status' => 'active',
                    'lifetime_access' => true,
                    'enrolled_at' => now(),
                ],
            );

            if ($enrollment->wasRecentlyCreated) {
                $course->increment('enrollment_count');
            }
        });
    }

    private function redirectToPaystation(
        PaystationPaymentService $payments,
        Order $order,
        Payment $payment,
        string $phone,
    ): RedirectResponse {
        try {
            return redirect()->away($payments->initiate($order, $payment, $phone));
        } catch (Throwable $exception) {
            report($exception);

            $payment->update(['status' => 'failed']);
            $order->update(['status' => 'failed']);

            return back()
                ->withInput()
                ->withErrors(['payment' => $exception->getMessage() ?: __('app.checkout_verification_failed')]);
        }
    }

    private function courseSubtotal(Course $course): float
    {
        return round(Currency::convert((float) $course->price, $course->currency, 'BDT'), 2);
    }

    private function courseTotal(Course $course): float
    {
        $price = $course->discounted_price !== null
            ? (float) $course->discounted_price
            : (float) $course->price;

        return round(Currency::convert($price, $course->currency, 'BDT'), 2);
    }

    private function orderNumber(): string
    {
        do {
            $number = 'ND-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (Order::query()->where('order_number', $number)->exists());

        return $number;
    }

    private function invoiceNumber(): string
    {
        do {
            $number = 'PS-'.now()->format('YmdHis').'-'.Str::upper(Str::random(6));
        } while (Payment::query()->where('transaction_ref', $number)->exists());

        return $number;
    }
}
