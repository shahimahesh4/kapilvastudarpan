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
                Select::make('province')
                    ->options(array_combine($provinces = ['कोशी प्रदेश', 'मधेश प्रदेश', 'बागमती प्रदेश', 'गण्डकी प्रदेश', 'लुम्बिनी प्रदेश', 'कर्णाली प्रदेश', 'सुदूरपश्चिम प्रदेश'], $provinces))
                    ->searchable(),
                Textarea::make('excerpt')->rows(3)->columnSpanFull(),
                Textarea::make('body')->rows(10)->columnSpanFull(),
                TextInput::make('image_path')->label('Image URL or storage path')->columnSpanFull(),
                DateTimePicker::make('published_at'),
                Toggle::make('is_featured')->label('Feature on homepage'),
            ]);
    }
}
