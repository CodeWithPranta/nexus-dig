<?php

namespace App\Filament\Resources\PortfolioProjects\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PortfolioProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('service_category_id')
                    ->relationship('serviceCategory', 'name'),
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                FileUpload::make('featured_image')
                    ->image(),
                Textarea::make('gallery')
                    ->columnSpanFull(),
                Textarea::make('tech_stack')
                    ->columnSpanFull(),
                TextInput::make('project_url')
                    ->url(),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
                Toggle::make('is_featured')
                    ->required(),
                DatePicker::make('completed_at'),
            ]);
    }
}
