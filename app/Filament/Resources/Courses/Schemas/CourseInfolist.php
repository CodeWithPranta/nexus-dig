<?php

namespace App\Filament\Resources\Courses\Schemas;

use App\Models\Course;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CourseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('category.name')
                    ->label('Category')
                    ->placeholder('-'),
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('short_description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('thumbnail')
                    ->placeholder('-'),
                ImageEntry::make('cover_image')
                    ->placeholder('-'),
                TextEntry::make('promo_video_url')
                    ->placeholder('-'),
                TextEntry::make('language'),
                TextEntry::make('difficulty'),
                TextEntry::make('duration_hours')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('learning_objectives')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('prerequisites')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('target_audience')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('price')
                    ->money(fn (Course $record): string => $record->currency),
                TextEntry::make('discounted_price')
                    ->money(fn (Course $record): string => $record->currency)
                    ->placeholder('-'),
                TextEntry::make('currency'),
                TextEntry::make('status'),
                IconEntry::make('is_featured')
                    ->boolean(),
                TextEntry::make('enrollment_count')
                    ->numeric(),
                TextEntry::make('seo_title')
                    ->placeholder('-'),
                TextEntry::make('seo_description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                ImageEntry::make('seo_image')
                    ->placeholder('-'),
                TextEntry::make('published_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('archived_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Course $record): bool => $record->trashed()),
            ]);
    }
}
