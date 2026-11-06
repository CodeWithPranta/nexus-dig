<?php

namespace App\Filament\Resources\Recordings;

use App\Filament\Resources\Recordings\Pages\CreateRecording;
use App\Filament\Resources\Recordings\Pages\EditRecording;
use App\Filament\Resources\Recordings\Pages\ListRecordings;
use App\Filament\Resources\Recordings\Schemas\RecordingForm;
use App\Filament\Resources\Recordings\Tables\RecordingsTable;
use App\Models\Recording;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class RecordingResource extends Resource
{
    protected static ?string $model = Recording::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Education';

    protected static ?int $navigationSort = 60;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-film';

    public static function form(Schema $schema): Schema
    {
        return RecordingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RecordingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRecordings::route('/'),
            'create' => CreateRecording::route('/create'),
            'edit' => EditRecording::route('/{record}/edit'),
        ];
    }
}
