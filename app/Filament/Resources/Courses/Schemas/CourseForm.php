<?php

namespace App\Filament\Resources\Courses\Schemas;

use App\Models\Course;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Course')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Details')
                            ->columns(2)
                            ->schema([
                                Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload(),
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                Select::make('language')
                                    ->options([
                                        'English' => 'English',
                                        'Bangla' => 'Bangla',
                                        'Arabic' => 'Arabic',
                                        'Hindi' => 'Hindi',
                                        'Spanish' => 'Spanish',
                                    ])
                                    ->default('English')
                                    ->required(),
                                Select::make('difficulty')
                                    ->options(Course::difficultyOptions())
                                    ->default('beginner')
                                    ->required(),
                                TextInput::make('duration_hours')
                                    ->numeric()
                                    ->minValue(0),
                                Textarea::make('short_description')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                RichEditor::make('description')
                                    ->columnSpanFull(),
                            ]),
                        Tab::make('Media')
                            ->columns(2)
                            ->schema([
                                FileUpload::make('thumbnail')
                                    ->image()
                                    ->directory('courses')
                                    ->imageEditor(),
                                FileUpload::make('cover_image')
                                    ->image()
                                    ->directory('courses')
                                    ->imageEditor(),
                                TextInput::make('promo_video_url')
                                    ->url()
                                    ->columnSpanFull(),
                            ]),
                        Tab::make('Content')
                            ->schema([
                                TagsInput::make('learning_objectives')
                                    ->columnSpanFull(),
                                TagsInput::make('prerequisites')
                                    ->columnSpanFull(),
                                TagsInput::make('target_audience')
                                    ->columnSpanFull(),
                            ]),
                        Tab::make('Pricing & publishing')
                            ->columns(2)
                            ->schema([
                                TextInput::make('price')
                                    ->numeric()
                                    ->prefix(fn (callable $get): string => (string) config('currency.supported.'.($get('currency') ?? 'BDT').'.symbol', '৳'))
                                    ->required()
                                    ->default(0),
                                TextInput::make('discounted_price')
                                    ->numeric()
                                    ->prefix(fn (callable $get): string => (string) config('currency.supported.'.($get('currency') ?? 'BDT').'.symbol', '৳')),
                                Select::make('currency')
                                    ->options([
                                        'BDT' => '৳ BDT',
                                        'USD' => '$ USD',
                                    ])
                                    ->default('BDT')
                                    ->required()
                                    ->live(),
                                Select::make('status')
                                    ->options(Course::statusOptions())
                                    ->default(Course::STATUS_DRAFT)
                                    ->required(),
                                Toggle::make('is_featured'),
                                DateTimePicker::make('published_at'),
                            ]),
                        Tab::make('SEO')
                            ->columns(2)
                            ->schema([
                                TextInput::make('seo_title')
                                    ->maxLength(255),
                                FileUpload::make('seo_image')
                                    ->image()
                                    ->directory('seo'),
                                Textarea::make('seo_description')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
