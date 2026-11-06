<?php

namespace App\Filament\Resources\LeadActivities\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LeadActivityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('service_inquiry_id')
                    ->required()
                    ->numeric(),
                Select::make('user_id')
                    ->relationship('user', 'name'),
                TextInput::make('type')
                    ->required()
                    ->default('note'),
                Textarea::make('note')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
