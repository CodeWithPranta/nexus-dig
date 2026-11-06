<?php

namespace App\Filament\Resources\Courses\Tables;

use App\Models\Course;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Cover'),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),
                TextColumn::make('difficulty')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'beginner' => 'success',
                        'intermediate' => 'warning',
                        'advanced' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('price')
                    ->money(fn (Course $record): string => $record->currency)
                    ->sortable(),
                TextColumn::make('discounted_price')
                    ->money(fn (Course $record): string => $record->currency)
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Course::statusOptions()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        Course::STATUS_PUBLISHED => 'success',
                        Course::STATUS_PENDING => 'warning',
                        Course::STATUS_REJECTED => 'danger',
                        default => 'gray',
                    }),
                IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured'),
                TextColumn::make('enrollment_count')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(Course::statusOptions()),
                SelectFilter::make('difficulty')
                    ->options(Course::difficultyOptions()),
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Course $record): bool => $record->status === Course::STATUS_PENDING)
                    ->requiresConfirmation()
                    ->action(function (Course $record): void {
                        $record->update([
                            'status' => Course::STATUS_PUBLISHED,
                            'published_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Course approved and published')
                            ->success()
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Course $record): bool => $record->status === Course::STATUS_PENDING)
                    ->requiresConfirmation()
                    ->action(function (Course $record): void {
                        $record->update([
                            'status' => Course::STATUS_REJECTED,
                        ]);

                        Notification::make()
                            ->title('Course rejected')
                            ->warning()
                            ->send();
                    }),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
