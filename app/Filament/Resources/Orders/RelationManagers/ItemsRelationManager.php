<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload(),
                TextInput::make('course_title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('price')
                    ->numeric()
                    ->prefix(fn (): string => (string) config('currency.supported.'.($this->getOwnerRecord()->currency ?? 'BDT').'.symbol', '৳'))
                    ->required()
                    ->default(0),
                TextInput::make('discount')
                    ->numeric()
                    ->prefix(fn (): string => (string) config('currency.supported.'.($this->getOwnerRecord()->currency ?? 'BDT').'.symbol', '৳'))
                    ->required()
                    ->default(0),
                TextInput::make('total')
                    ->numeric()
                    ->prefix(fn (): string => (string) config('currency.supported.'.($this->getOwnerRecord()->currency ?? 'BDT').'.symbol', '৳'))
                    ->required()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('course_title')
            ->columns([
                TextColumn::make('course_title')
                    ->label('Course')
                    ->searchable(),
                TextColumn::make('price')
                    ->money(fn ($record): string => $record->order?->currency ?? 'BDT'),
                TextColumn::make('discount')
                    ->money(fn ($record): string => $record->order?->currency ?? 'BDT'),
                TextColumn::make('total')
                    ->money(fn ($record): string => $record->order?->currency ?? 'BDT'),
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
