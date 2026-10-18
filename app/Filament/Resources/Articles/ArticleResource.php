<?php

namespace App\Filament\Resources\Articles;

use App\Filament\Resources\Articles\Pages\CreateArticle;
use App\Filament\Resources\Articles\Pages\EditArticle;
use App\Filament\Resources\Articles\Pages\ListArticles;
use App\Filament\Resources\Articles\Schemas\ArticleForm;
use App\Filament\Resources\Articles\Tables\ArticlesTable;
use App\Models\Article;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Articles';

    protected static ?string $modelLabel = 'Article';

    protected static ?string $pluralModelLabel = 'Articles';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return ArticleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ArticlesTable::configure($table);
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
            'index' => ListArticles::route('/'),
            'create' => CreateArticle::route('/create'),
            'edit' => EditArticle::route('/{record}/edit'),
        ];
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        if ($user->isPublisher()) {
            return $record->user_id === $user->id;
        }

        return true;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        if ($user->isPublisher()) {
            return $record->user_id === $user->id && $record->status === 'draft';
        }

        return true;
    }

    public static function canDeleteAny(): bool
    {
        $user = auth()->user();
        if ($user && $user->isPublisher()) {
            return false;
        }

        return true;
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery()
            ->withoutGlobalScopes([
                \Illuminate\Database\Eloquent\SoftDeletingScope::class,
            ]);

        $user = auth()->user();
        if ($user && $user->isPublisher()) {
            $query->where('user_id', $user->id);
        }

        return $query;
    }

    public static function getRecordRouteBindingEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                \Illuminate\Database\Eloquent\SoftDeletingScope::class,
            ]);

        $user = auth()->user();
        if ($user && $user->isPublisher()) {
            $query->where('user_id', $user->id);
        }

        return $query;
    }
}
