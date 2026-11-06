<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class PendingCourses extends TableWidget
{
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Pending course approvals')
            ->query(fn (): Builder => Course::query()->where('status', Course::STATUS_PENDING)->latest()->limit(5))
            ->paginated(false)
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('category.name')
                    ->label('Category'),
                TextColumn::make('difficulty')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Submitted')
                    ->since(),
            ]);
    }
}
