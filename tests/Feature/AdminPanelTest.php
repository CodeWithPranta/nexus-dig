<?php

use App\Enums\Role;
use App\Filament\Widgets\StatsOverview;
use App\Models\Order;
use App\Models\ServiceInquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createSuperAdminUser(): User
{
    return User::factory()->create([
        'role' => Role::SuperAdmin,
        'status' => 'active',
    ]);
}

it('renders the admin login page', function () {
    $this->get('/admin/login')->assertOk();
});

it('lets a super admin access the admin dashboard', function () {
    $this->actingAs(createSuperAdminUser())
        ->get('/admin')
        ->assertOk();
});

it('shows dashboard revenue in BDT by default', function () {
    Order::create([
        'user_id' => User::factory()->create()->id,
        'order_number' => 'ND-REVENUE-1',
        'subtotal' => 35800,
        'discount_total' => 0,
        'total' => 35800,
        'currency' => 'BDT',
        'status' => 'paid',
        'paid_at' => now(),
    ]);

    $widget = new class extends StatsOverview
    {
        public function stats(): array
        {
            return $this->getStats();
        }
    };

    expect($widget->stats()[0]->getValue())->toBe('৳৩৫,৮০০');
});

it('blocks students from the admin panel', function () {
    $student = User::factory()->create(['role' => Role::Student]);

    $this->actingAs($student)
        ->get('/admin')
        ->assertForbidden();
});

it('renders the core admin resource pages', function (string $path) {
    $this->actingAs(createSuperAdminUser())
        ->get($path)
        ->assertOk();
})->with([
    'courses list' => '/admin/courses',
    'courses create' => '/admin/courses/create',
    'lessons list' => '/admin/lessons',
    'lessons create' => '/admin/lessons/create',
    'users list' => '/admin/users',
    'users create' => '/admin/users/create',
    'services list' => '/admin/services',
    'orders list' => '/admin/orders',
    'inquiries list' => '/admin/service-inquiries',
    'audit logs list' => '/admin/audit-logs',
    'blog posts list' => '/admin/blog-posts',
    'categories list' => '/admin/categories',
    'class sessions list' => '/admin/class-sessions',
    'coupon usages list' => '/admin/coupon-usages',
    'coupons list' => '/admin/coupons',
    'course sections list' => '/admin/course-sections',
    'enrollments list' => '/admin/enrollments',
    'faqs list' => '/admin/faqs',
    'lead activities list' => '/admin/lead-activities',
    'lesson progress list' => '/admin/lesson-progress',
    'order items list' => '/admin/order-items',
    'pages list' => '/admin/pages',
    'payments list' => '/admin/payments',
    'portfolio projects list' => '/admin/portfolio-projects',
    'recordings list' => '/admin/recordings',
    'refunds list' => '/admin/refunds',
    'service categories list' => '/admin/service-categories',
    'site settings list' => '/admin/site-settings',
    'testimonials list' => '/admin/testimonials',
]);

it('renders order and inquiry edit pages with their relation managers', function () {
    $admin = createSuperAdminUser();
    $student = User::factory()->create();

    $order = Order::create([
        'user_id' => $student->id,
        'order_number' => 'ND-TEST-1',
        'subtotal' => 100,
        'discount_total' => 0,
        'total' => 100,
        'currency' => 'USD',
        'status' => 'paid',
        'paid_at' => now(),
    ]);

    $order->items()->create([
        'course_title' => 'Sample course',
        'price' => 100,
        'discount' => 0,
        'total' => 100,
    ]);

    $order->payments()->create([
        'provider' => 'stripe',
        'transaction_ref' => 'TXN-TEST-1',
        'amount' => 100,
        'currency' => 'USD',
        'status' => 'succeeded',
        'paid_at' => now(),
    ]);

    $inquiry = ServiceInquiry::create([
        'name' => 'Test Lead',
        'email' => 'lead-test@example.com',
        'project_description' => 'Test project',
        'status' => 'new',
    ]);

    $this->actingAs($admin)
        ->get("/admin/orders/{$order->id}/edit")
        ->assertOk();

    $this->actingAs($admin)
        ->get("/admin/service-inquiries/{$inquiry->id}/edit")
        ->assertOk();
});
