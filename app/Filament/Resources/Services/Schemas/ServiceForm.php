<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Support\Currency;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('service_category_id')
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('short_description')
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->columnSpanFull(),
                FileUpload::make('cover_image')
                    ->image(),
                Textarea::make('gallery')
                    ->columnSpanFull(),
                Textarea::make('features')
                    ->columnSpanFull(),
                Textarea::make('deliverables')
                    ->columnSpanFull(),
                Textarea::make('technologies')
                    ->columnSpanFull(),
                TextInput::make('starting_price')
                    ->numeric()
                    ->prefix(fn (): string => (string) config('currency.supported.'.Currency::current().'.symbol', '৳')),
                TextInput::make('price_note'),
                TextInput::make('delivery_range'),
                Textarea::make('faqs')
                    ->columnSpanFull(),
                Toggle::make('is_featured')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('seo_title'),
                Textarea::make('seo_description')
                    ->columnSpanFull(),
                FileUpload::make('seo_image')
                    ->image(),
            ]);
    }
}
