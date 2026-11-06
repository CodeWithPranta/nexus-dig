<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('provider')
                    ->options([
                        'stripe' => 'Stripe',
                        'paypal' => 'PayPal',
                        'sslcommerz' => 'SSLCommerz',
                        'bkash' => 'bKash',
                        'manual' => 'Manual',
                    ])
                    ->required(),
                TextInput::make('transaction_ref')
                    ->maxLength(255),
                TextInput::make('amount')
                    ->numeric()
                    ->prefix(fn (callable $get): string => (string) config('currency.supported.'.($get('currency') ?? 'BDT').'.symbol', '৳'))
                    ->required(),
                Select::make('currency')
                    ->options([
                        'BDT' => '৳ BDT',
                        'USD' => '$ USD',
                    ])
                    ->default('BDT')
                    ->required(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'succeeded' => 'Succeeded',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ])
                    ->default('pending')
                    ->required(),
                DateTimePicker::make('paid_at'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('transaction_ref')
            ->columns([
                TextColumn::make('provider')
                    ->badge(),
                TextColumn::make('transaction_ref')
                    ->label('Reference')
                    ->searchable(),
                TextColumn::make('amount')
                    ->money(fn ($record): string => $record->currency ?? 'BDT'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'succeeded' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        'refunded' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
