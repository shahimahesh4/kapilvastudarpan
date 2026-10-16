<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Models\Category;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->live(onBlur: true),
            TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Textarea::make('description')->rows(3)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable()->sortable(),
            TextColumn::make('slug')->searchable(),
            TextColumn::make('articles_count')->counts('articles')->label('Articles'),
        ])->recordActions([\Filament\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => ListCategories::route('/'), 'create' => CreateCategory::route('/create'), 'edit' => EditCategory::route('/{record}/edit')];
    }
}
