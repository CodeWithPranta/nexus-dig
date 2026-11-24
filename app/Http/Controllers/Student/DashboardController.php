<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassSession;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Recording;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->isSuperAdmin() || $user->isTeacher()) {
            return redirect('/admin');
        }

        $enrollments = $user->enrollments()
            ->with('course')
            ->where('status', 'active')
            ->latest('enrolled_at')
            ->get()
            ->filter(fn (Enrollment $enrollment): bool => $enrollment->course !== null)
            ->values();

        $courseIds = $enrollments->pluck('course_id')->all();

        $lessonTotals = Lesson::query()
            ->join('course_sections', 'course_sections.id', '=', 'lessons.course_section_id')
            ->whereIn('course_sections.course_id', $courseIds)
            ->where('lessons.status', Lesson::STATUS_PUBLISHED)
            ->groupBy('course_sections.course_id')
            ->selectRaw('course_sections.course_id as course_id, count(*) as aggregate')
            ->pluck('aggregate', 'course_id');

        $completedTotals = LessonProgress::query()
            ->join('lessons', 'lessons.id', '=', 'lesson_progress.lesson_id')
            ->join('course_sections', 'course_sections.id', '=', 'lessons.course_section_id')
            ->where('lesson_progress.user_id', $user->id)
            ->where('lesson_progress.status', 'completed')
            ->whereIn('course_sections.course_id', $courseIds)
            ->groupBy('course_sections.course_id')
            ->selectRaw('course_sections.course_id as course_id, count(*) as aggregate')
            ->pluck('aggregate', 'course_id');

        $completedLessonIds = $user->lessonProgress()
            ->where('status', 'completed')
            ->pluck('lesson_id');

        $nextLessons = Lesson::query()
            ->join('course_sections', 'course_sections.id', '=', 'lessons.course_section_id')
            ->whereIn('course_sections.course_id', $courseIds)
            ->where('lessons.status', Lesson::STATUS_PUBLISHED)
            ->when($completedLessonIds->isNotEmpty(), fn ($query) => $query->whereNotIn('lessons.id', $completedLessonIds))
            ->orderBy('course_sections.sort_order')
            ->orderBy('lessons.sort_order')
            ->select('lessons.*', 'course_sections.course_id as course_id')
            ->get()
            ->unique('course_id')
            ->keyBy('course_id');

        $upcomingSessions = ClassSession::query()
            ->with('course')
            ->whereIn('course_id', $courseIds)
            ->whereIn('status', ['scheduled', 'live'])
            ->whereNotNull('starts_at')
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at')
            ->limit(3)
            ->get();

        $recordings = Recording::query()
            ->whereIn('course_id', $courseIds)
            ->where('status', 'published')
            ->latest('published_at')
            ->limit(5)
            ->get();

        $orders = $user->orders()
            ->latest()
            ->limit(5)
            ->get();

        return view('student.dashboard', [
            'user' => $user,
            'enrollments' => $enrollments,
            'lessonTotals' => $lessonTotals,
            'completedTotals' => $completedTotals,
            'nextLessons' => $nextLessons,
            'upcomingSessions' => $upcomingSessions,
            'recordings' => $recordings,
            'orders' => $orders,
            'stats' => [
                'courses' => $enrollments->count(),
                'completed' => (int) $completedTotals->sum(),
                'upcoming' => $upcomingSessions->count(),
            ],
        ]);
    }
}
