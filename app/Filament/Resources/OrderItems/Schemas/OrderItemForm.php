<?php

namespace App\Filament\Resources\OrderItems\Schemas;

use App\Support\Currency;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('order_id')
                    ->relationship('order', 'id')
                    ->required(),
                Select::make('course_id')
                    ->relationship('course', 'title'),
                TextInput::make('course_title')
                    ->required(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix(fn (): string => (string) config('currency.supported.'.Currency::current().'.symbol', '৳')),
                TextInput::make('discount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total')
                    ->required()
                    ->numeric(),
            ]);
    }
}
