<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('role'),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('avatar'),
                TextInput::make('rating')
                    ->numeric(),
                Toggle::make('is_approved')
                    ->required(),
            ]);
    }
}
