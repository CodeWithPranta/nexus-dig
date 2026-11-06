<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use App\Enums\Role;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InstructorsRelationManager extends RelationManager
{
    protected static string $relationship = 'instructors';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('role')
                    ->options([
                        'teacher' => 'Teacher',
                        'assistant' => 'Assistant',
                        'mentor' => 'Mentor',
                    ])
                    ->default('teacher')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('role')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state instanceof Role ? $state->label() : (Role::tryFrom((string) $state)?->label() ?? (string) $state))
                    ->color(fn ($state): string => match ($state instanceof Role ? $state->value : (string) $state) {
                        Role::SuperAdmin->value => 'danger',
                        Role::Teacher->value => 'warning',
                        Role::Student->value => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('pivot.role')
                    ->label('Course role'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn (Builder $query): Builder => $query->where('role', Role::Teacher->value))
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Select::make('role')
                            ->options([
                                'teacher' => 'Teacher',
                                'assistant' => 'Assistant',
                                'mentor' => 'Mentor',
                            ])
                            ->default('teacher')
                            ->required(),
                    ]),
            ])
            ->recordActions([
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
