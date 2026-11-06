<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;

class LessonPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isTeacher();
    }

    public function view(User $user, Lesson $lesson): bool
    {
        return $this->canManage($user, $lesson);
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isTeacher();
    }

    public function update(User $user, Lesson $lesson): bool
    {
        return $this->canManage($user, $lesson);
    }

    public function delete(User $user, Lesson $lesson): bool
    {
        return $this->canManage($user, $lesson);
    }

    public function deleteAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function reorder(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isTeacher();
    }

    private function canManage(User $user, Lesson $lesson): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        $course = $lesson->section?->course;

        return $user->isTeacher() && $course !== null && $course->hasInstructor($user);
    }
}
