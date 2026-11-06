<?php

namespace App\Filament\Resources\LessonProgress\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LessonProgressForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('lesson_id')
                    ->relationship('lesson', 'title')
                    ->required(),
                Select::make('enrollment_id')
                    ->relationship('enrollment', 'id'),
                TextInput::make('status')
                    ->required()
                    ->default('not_started'),
                DateTimePicker::make('completed_at'),
            ]);
    }
}
