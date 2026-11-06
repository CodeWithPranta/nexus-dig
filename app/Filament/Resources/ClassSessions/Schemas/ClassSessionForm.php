<?php

namespace App\Filament\Resources\ClassSessions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClassSessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->required(),
                Select::make('lesson_id')
                    ->relationship('lesson', 'title'),
                Select::make('instructor_id')
                    ->relationship('instructor', 'name'),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                DateTimePicker::make('starts_at')
                    ->required(),
                DateTimePicker::make('ends_at'),
                TextInput::make('timezone')
                    ->required()
                    ->default('UTC'),
                TextInput::make('meeting_provider'),
                Textarea::make('meeting_url')
                    ->columnSpanFull(),
                Textarea::make('access_instructions')
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('scheduled'),
            ]);
    }
}
