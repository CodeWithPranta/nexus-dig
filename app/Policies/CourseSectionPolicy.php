<?php

namespace App\Policies;

use App\Models\CourseSection;
use App\Models\User;

class CourseSectionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isTeacher();
    }

    public function view(User $user, CourseSection $section): bool
    {
        return $this->canManage($user, $section);
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isTeacher();
    }

    public function update(User $user, CourseSection $section): bool
    {
        return $this->canManage($user, $section);
    }

    public function delete(User $user, CourseSection $section): bool
    {
        return $this->canManage($user, $section);
    }

    public function deleteAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function reorder(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isTeacher();
    }

    private function canManage(User $user, CourseSection $section): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isTeacher() && $section->course !== null && $section->course->hasInstructor($user);
    }
}
