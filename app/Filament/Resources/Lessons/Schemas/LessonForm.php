<?php

namespace App\Filament\Resources\Lessons\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LessonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Lesson')
                    ->columns(2)
                    ->schema([
                        Select::make('course_section_id')
                            ->relationship('section', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('type')
                            ->options([
                                'video' => 'Video',
                                'text' => 'Text',
                                'quiz' => 'Quiz',
                                'live' => 'Live session',
                                'audio' => 'Audio',
                                'document' => 'Document',
                            ])
                            ->default('video')
                            ->required(),
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('duration_minutes')
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        TextInput::make('video_url')
                            ->url()
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->columnSpanFull(),
                    ]),
                Section::make('Publishing')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_preview')
                            ->label('Free preview'),
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'pending' => 'Pending review',
                                'published' => 'Published',
                                'rejected' => 'Rejected',
                            ])
                            ->default('draft')
                            ->required(),
                        DateTimePicker::make('published_at'),
                    ]),
            ]);
    }
}
