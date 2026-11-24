<?php

namespace App\Providers;

use App\Models\AuditLog;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\ClassSession;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Enrollment;
use App\Models\Faq;
use App\Models\LeadActivity;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\LessonResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Page;
use App\Models\Payment;
use App\Models\PortfolioProject;
use App\Models\Recording;
use App\Models\Refund;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceInquiry;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\User;
use App\Observers\AuditObserver;
use App\Policies\ClassSessionPolicy;
use App\Policies\CoursePolicy;
use App\Policies\CourseSectionPolicy;
use App\Policies\LessonPolicy;
use App\Policies\RecordingPolicy;
use App\Policies\SuperAdminOnlyPolicy;
use App\Support\Filesystem;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->extend('files', fn (): Filesystem => new Filesystem);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function (User $user, string $ability): ?bool {
            return $user->isSuperAdmin() ? true : null;
        });

        $superAdminOnlyModels = [
            User::class,
            Category::class,
            Order::class,
            OrderItem::class,
            Payment::class,
            Refund::class,
            Coupon::class,
            CouponUsage::class,
            Enrollment::class,
            LessonProgress::class,
            LessonResource::class,
            ServiceCategory::class,
            Service::class,
            PortfolioProject::class,
            ServiceInquiry::class,
            LeadActivity::class,
            BlogPost::class,
            Page::class,
            Faq::class,
            Testimonial::class,
            AuditLog::class,
            SiteSetting::class,
        ];

        foreach ($superAdminOnlyModels as $model) {
            Gate::policy($model, SuperAdminOnlyPolicy::class);
        }

        Gate::policy(Course::class, CoursePolicy::class);
        Gate::policy(CourseSection::class, CourseSectionPolicy::class);
        Gate::policy(Lesson::class, LessonPolicy::class);
        Gate::policy(ClassSession::class, ClassSessionPolicy::class);
        Gate::policy(Recording::class, RecordingPolicy::class);

        Course::observe(AuditObserver::class);
        Order::observe(AuditObserver::class);
        Payment::observe(AuditObserver::class);
        Service::observe(AuditObserver::class);
        ServiceInquiry::observe(AuditObserver::class);
        Coupon::observe(AuditObserver::class);

        Event::listen(Login::class, function (Login $event): void {
            if ($event->user instanceof User) {
                $event->user->forceFill(['last_login_at' => now()])->saveQuietly();
            }
        });
    }
}
