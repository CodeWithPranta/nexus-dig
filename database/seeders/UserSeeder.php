<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use App\Support\Permissions;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@nexusdig.com'],
            [
                'name' => 'NexusDig Admin',
                'password' => 'password',
                'role' => Role::SuperAdmin,
                'status' => 'active',
                'email_verified_at' => now(),
                'approved_at' => now(),
            ],
        );

        User::updateOrCreate(
            ['email' => 'teacher@nexusdig.com'],
            [
                'name' => 'Ayesha Rahman',
                'password' => 'password',
                'role' => Role::Teacher,
                'status' => 'active',
                'email_verified_at' => now(),
                'approved_at' => now(),
                'headline' => 'Senior digital marketing instructor',
                'bio' => 'Ayesha has helped 200+ brands grow through SEO and paid acquisition.',
                'permissions' => [
                    Permissions::MANAGE_COURSES,
                    Permissions::SUBMIT_LESSONS,
                    Permissions::PROPOSE_SCHEDULES,
                    Permissions::VIEW_STUDENT_PROGRESS,
                ],
            ],
        );

        User::updateOrCreate(
            ['email' => 'student@nexusdig.com'],
            [
                'name' => 'Rafiq Hasan',
                'password' => 'password',
                'role' => Role::Student,
                'status' => 'active',
                'email_verified_at' => now(),
            ],
        );

        if (User::where('role', Role::Student->value)->count() < 10) {
            User::factory()
                ->count(8)
                ->create([
                    'role' => Role::Student,
                    'status' => 'active',
                ]);
        }
    }
}
