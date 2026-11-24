<?php

namespace App\Filament\Resources\Enrollments\Schemas;

use App\Models\Enrollment;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->required(),
                Select::make('order_id')
                    ->relationship('order', 'id'),
                Select::make('status')
                    ->options(Enrollment::statusOptions())
                    ->default('active')
                    ->required(),
                Toggle::make('lifetime_access')
                    ->default(true)
                    ->required(),
                DateTimePicker::make('access_expires_at'),
                DateTimePicker::make('enrolled_at'),
            ]);
    }
}
