<?php

namespace App\Filament\Resources\ServiceInquiries\Schemas;

use App\Models\ServiceInquiry;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceInquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Lead details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(30),
                        Select::make('service_id')
                            ->relationship('service', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('budget_range')
                            ->maxLength(255),
                        TextInput::make('timeline')
                            ->maxLength(255),
                        TextInput::make('source')
                            ->maxLength(255),
                        FileUpload::make('attachment_path')
                            ->directory('inquiries'),
                        Textarea::make('project_description')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),
                Section::make('Pipeline')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->options(ServiceInquiry::statusOptions())
                            ->default('new')
                            ->required(),
                        Select::make('assigned_to')
                            ->label('Assigned to')
                            ->relationship('assignee', 'name')
                            ->searchable()
                            ->preload(),
                        Textarea::make('internal_notes')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
