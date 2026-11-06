<?php

namespace App\Filament\Resources\CourseSections;

use App\Filament\Resources\CourseSections\Pages\CreateCourseSection;
use App\Filament\Resources\CourseSections\Pages\EditCourseSection;
use App\Filament\Resources\CourseSections\Pages\ListCourseSections;
use App\Filament\Resources\CourseSections\Schemas\CourseSectionForm;
use App\Filament\Resources\CourseSections\Tables\CourseSectionsTable;
use App\Models\CourseSection;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class CourseSectionResource extends Resource
{
    protected static ?string $model = CourseSection::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Education';

    protected static ?int $navigationSort = 30;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return CourseSectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CourseSectionsTable::configure($table);
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
            'index' => ListCourseSections::route('/'),
            'create' => CreateCourseSection::route('/create'),
            'edit' => EditCourseSection::route('/{record}/edit'),
        ];
    }
}
