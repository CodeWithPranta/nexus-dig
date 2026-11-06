<?php

namespace App\Filament\Resources\Lessons\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ResourcesRelationManager extends RelationManager
{
    protected static string $relationship = 'resources';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Select::make('type')
                    ->options([
                        'file' => 'File download',
                        'link' => 'External link',
                        'video' => 'Video',
                        'document' => 'Document',
                    ])
                    ->default('file')
                    ->required(),
                FileUpload::make('file_path')
                    ->label('Upload file')
                    ->directory('lesson-resources')
                    ->columnSpanFull(),
                TextInput::make('url')
                    ->label('Or external URL')
                    ->url()
                    ->columnSpanFull(),
                Toggle::make('is_downloadable')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('file_path')
                    ->label('File')
                    ->toggleable(),
                TextColumn::make('url')
                    ->label('URL')
                    ->toggleable(),
                IconColumn::make('is_downloadable')
                    ->boolean()
                    ->label('Downloadable'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
