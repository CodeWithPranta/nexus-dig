<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isTeacher();
    }

    public function view(User $user, Course $course): bool
    {
        return $user->isSuperAdmin() || ($user->isTeacher() && $course->hasInstructor($user));
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function update(User $user, Course $course): bool
    {
        return $user->isSuperAdmin() || ($user->isTeacher() && $course->hasInstructor($user));
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->isSuperAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function restore(User $user, Course $course): bool
    {
        return $user->isSuperAdmin();
    }

    public function forceDelete(User $user, Course $course): bool
    {
        return $user->isSuperAdmin();
    }

    public function publish(User $user, Course $course): bool
    {
        return $user->isSuperAdmin();
    }
}
