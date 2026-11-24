<?php

use App\Models\ClassSession;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Order;
use App\Models\Recording;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * @return array{0: Course, 1: Lesson, 2: Lesson}
 */
function createDashboardCourse(): array
{
    $course = Course::create([
        'title' => 'Dashboard Course',
        'slug' => 'dashboard-course',
        'short_description' => 'A course for the student dashboard test.',
        'price' => 1500,
        'currency' => 'BDT',
        'status' => Course::STATUS_PUBLISHED,
        'published_at' => now(),
    ]);

    $section = CourseSection::create([
        'course_id' => $course->id,
        'title' => 'Getting started',
        'sort_order' => 0,
        'status' => 'published',
    ]);

    $firstLesson = Lesson::create([
        'course_section_id' => $section->id,
        'title' => 'First dashboard lesson',
        'slug' => 'first-dashboard-lesson',
        'type' => 'video',
        'duration_minutes' => 10,
        'sort_order' => 0,
        'status' => Lesson::STATUS_PUBLISHED,
        'published_at' => now(),
    ]);

    $secondLesson = Lesson::create([
        'course_section_id' => $section->id,
        'title' => 'Second dashboard lesson',
        'slug' => 'second-dashboard-lesson',
        'type' => 'video',
        'duration_minutes' => 20,
        'sort_order' => 1,
        'status' => Lesson::STATUS_PUBLISHED,
        'published_at' => now(),
    ]);

    return [$course, $firstLesson, $secondLesson];
}

it('shows enrolled courses, progress, orders, classes and recordings', function () {
    $student = User::factory()->student()->create();
    [$course, $firstLesson, $secondLesson] = createDashboardCourse();

    $enrollment = Enrollment::create([
        'user_id' => $student->id,
        'course_id' => $course->id,
        'status' => 'active',
        'lifetime_access' => true,
        'enrolled_at' => now(),
    ]);

    LessonProgress::create([
        'user_id' => $student->id,
        'lesson_id' => $firstLesson->id,
        'enrollment_id' => $enrollment->id,
        'status' => 'completed',
        'completed_at' => now(),
    ]);

    Order::create([
        'user_id' => $student->id,
        'order_number' => 'ND-DASH-1',
        'subtotal' => 1500,
        'discount_total' => 0,
        'total' => 1500,
        'currency' => 'BDT',
        'status' => 'paid',
        'paid_at' => now(),
    ]);

    ClassSession::create([
        'course_id' => $course->id,
        'title' => 'Live dashboard class',
        'starts_at' => now()->addDay(),
        'ends_at' => now()->addDay()->addHour(),
        'timezone' => 'Asia/Dhaka',
        'status' => 'scheduled',
        'meeting_url' => 'https://example.com/live',
    ]);

    Recording::create([
        'course_id' => $course->id,
        'title' => 'Dashboard recording',
        'video_url' => 'https://example.com/recording',
        'status' => 'published',
        'published_at' => now(),
    ]);

    $this->actingAs($student)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee($course->title)
        ->assertSee($secondLesson->title)
        ->assertSee('ND-DASH-1')
        ->assertSee('$12.50')
        ->assertSee('Live dashboard class')
        ->assertSee('Dashboard recording');
});

it('shows an empty state for students without enrollments', function () {
    $student = User::factory()->student()->create();

    $this->actingAs($student)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee(__('app.no_enrollments'));
});

it('sends staff to the admin panel instead of the student dashboard', function () {
    $this->actingAs(User::factory()->teacher()->create())
        ->get('/dashboard')
        ->assertRedirect('/admin');

    $this->actingAs(User::factory()->superAdmin()->create())
        ->get('/dashboard')
        ->assertRedirect('/admin');
});
