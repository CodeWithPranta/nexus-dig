<?php

namespace App\Filament\Widgets;

use App\Models\ServiceInquiry;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class NewInquiries extends TableWidget
{
    protected static ?int $sort = 4;

    public function table(Table $table): Table
    {
        return $table
            ->heading('New service inquiries')
            ->query(fn (): Builder => ServiceInquiry::query()->where('status', 'new')->latest()->limit(5))
            ->paginated(false)
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('service.name')
                    ->label('Service'),
                TextColumn::make('budget_range')
                    ->label('Budget'),
                TextColumn::make('created_at')
                    ->label('Received')
                    ->since(),
            ]);
    }
}
