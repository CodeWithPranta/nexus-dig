<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Role;
use App\Support\Permissions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Account')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->password()
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->maxLength(255),
                        Select::make('role')
                            ->options(Role::options())
                            ->default(Role::Student->value)
                            ->required()
                            ->live(),
                        Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'pending' => 'Pending approval',
                                'suspended' => 'Suspended',
                            ])
                            ->default('active')
                            ->required(),
                        DateTimePicker::make('approved_at'),
                    ])
                    ->columns(2),
                Section::make('Profile')
                    ->schema([
                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(30),
                        TextInput::make('headline')
                            ->maxLength(255),
                        Textarea::make('bio')
                            ->rows(4)
                            ->columnSpanFull(),
                        FileUpload::make('avatar_path')
                            ->image()
                            ->directory('avatars')
                            ->imageEditor(),
                    ])
                    ->columns(2),
                Section::make('Instructor permissions')
                    ->description('Only applies to teachers and mentors. Administrators always retain full control.')
                    ->schema([
                        Select::make('permissions')
                            ->multiple()
                            ->options(Permissions::options())
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (callable $get): bool => $get('role') === Role::Teacher->value),
            ]);
    }
}
