<?php

namespace App\Filament\Resources\LeadActivities;

use App\Filament\Resources\LeadActivities\Pages\CreateLeadActivity;
use App\Filament\Resources\LeadActivities\Pages\EditLeadActivity;
use App\Filament\Resources\LeadActivities\Pages\ListLeadActivities;
use App\Filament\Resources\LeadActivities\Schemas\LeadActivityForm;
use App\Filament\Resources\LeadActivities\Tables\LeadActivitiesTable;
use App\Models\LeadActivity;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class LeadActivityResource extends Resource
{
    protected static ?string $model = LeadActivity::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Services & CRM';

    protected static ?int $navigationSort = 50;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Schema $schema): Schema
    {
        return LeadActivityForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeadActivitiesTable::configure($table);
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
            'index' => ListLeadActivities::route('/'),
            'create' => CreateLeadActivity::route('/create'),
            'edit' => EditLeadActivity::route('/{record}/edit'),
        ];
    }
}
