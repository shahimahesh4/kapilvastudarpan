<?php

namespace App\Filament\Resources\Articles\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('title')->required()->live(onBlur: true)->columnSpanFull(),
                TextInput::make('slug')->required()->unique(ignoreRecord: true),
                TextInput::make('author')->required()->default('कपिलवस्तु दर्पण'),
                Textarea::make('excerpt')->rows(3)->columnSpanFull(),
                Textarea::make('body')->rows(10)->columnSpanFull(),
                TextInput::make('image_path')->label('Image URL or storage path')->columnSpanFull(),
                DateTimePicker::make('published_at'),
                Toggle::make('is_featured')->label('Feature on homepage'),
            ]);
    }
}
