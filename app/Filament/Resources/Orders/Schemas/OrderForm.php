<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('order_number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Select::make('coupon_id')
                            ->relationship('coupon', 'code')
                            ->searchable()
                            ->preload(),
                        Select::make('status')
                            ->options(Order::statusOptions())
                            ->default('pending')
                            ->required(),
                        Select::make('currency')
                            ->options([
                                'BDT' => '৳ BDT',
                                'USD' => '$ USD',
                            ])
                            ->default('BDT')
                            ->required()
                            ->live(),
                        DateTimePicker::make('paid_at'),
                    ]),
                Section::make('Totals')
                    ->columns(3)
                    ->schema([
                        TextInput::make('subtotal')
                            ->numeric()
                            ->prefix(fn (callable $get): string => (string) config('currency.supported.'.($get('currency') ?? 'BDT').'.symbol', '৳'))
                            ->required()
                            ->default(0),
                        TextInput::make('discount_total')
                            ->numeric()
                            ->prefix(fn (callable $get): string => (string) config('currency.supported.'.($get('currency') ?? 'BDT').'.symbol', '৳'))
                            ->required()
                            ->default(0),
                        TextInput::make('total')
                            ->numeric()
                            ->prefix(fn (callable $get): string => (string) config('currency.supported.'.($get('currency') ?? 'BDT').'.symbol', '৳'))
                            ->required()
                            ->default(0),
                    ]),
                Section::make('Notes')
                    ->schema([
                        Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
