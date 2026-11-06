<?php

namespace App\Support;

class Permissions
{
    public const MANAGE_COURSES = 'manage_courses';

    public const SUBMIT_LESSONS = 'submit_lessons';

    public const PROPOSE_SCHEDULES = 'propose_schedules';

    public const SUBMIT_RECORDINGS = 'submit_recordings';

    public const VIEW_STUDENT_PROGRESS = 'view_student_progress';

    public const MANAGE_PRICES = 'manage_prices';

    public const UPLOAD_RESOURCES = 'upload_resources';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::MANAGE_COURSES => 'Manage assigned courses',
            self::SUBMIT_LESSONS => 'Submit lessons for review',
            self::PROPOSE_SCHEDULES => 'Propose live class schedules',
            self::SUBMIT_RECORDINGS => 'Submit recording links',
            self::VIEW_STUDENT_PROGRESS => 'View student progress',
            self::MANAGE_PRICES => 'Manage course prices',
            self::UPLOAD_RESOURCES => 'Upload learning resources',
        ];
    }
}
