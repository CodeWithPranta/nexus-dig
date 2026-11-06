<?php

namespace App\Policies;

use App\Models\Recording;
use App\Models\User;

class RecordingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isTeacher();
    }

    public function view(User $user, Recording $recording): bool
    {
        return $this->canManage($user, $recording);
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isTeacher();
    }

    public function update(User $user, Recording $recording): bool
    {
        return $this->canManage($user, $recording);
    }

    public function delete(User $user, Recording $recording): bool
    {
        return $user->isSuperAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    private function canManage(User $user, Recording $recording): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isTeacher() && $recording->course !== null && $recording->course->hasInstructor($user);
    }
}
