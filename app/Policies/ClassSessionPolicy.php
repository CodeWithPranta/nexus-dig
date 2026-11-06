<?php

namespace App\Policies;

use App\Models\ClassSession;
use App\Models\User;

class ClassSessionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isTeacher();
    }

    public function view(User $user, ClassSession $session): bool
    {
        return $this->canManage($user, $session);
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isTeacher();
    }

    public function update(User $user, ClassSession $session): bool
    {
        return $this->canManage($user, $session);
    }

    public function delete(User $user, ClassSession $session): bool
    {
        return $user->isSuperAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    private function canManage(User $user, ClassSession $session): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isTeacher() && $session->course !== null && $session->course->hasInstructor($user);
    }
}
