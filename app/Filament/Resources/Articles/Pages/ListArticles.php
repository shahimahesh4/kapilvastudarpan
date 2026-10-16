<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use App\Models\Article;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListArticles extends ListRecords
{
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'total' => Tab::make('Total Articles')
                ->badge(fn () => Article::count())
                ->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed()),

            'published' => Tab::make('Published')
                ->badge(fn () => Article::where('is_published', true)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed()->where('is_published', true)),

            'unpublish' => Tab::make('Unpublish')
                ->badge(fn () => Article::where('is_published', false)->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed()->where('is_published', false)),

            'trash' => Tab::make('Trash')
                ->badge(fn () => Article::onlyTrashed()->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed()),
        ];
    }
}
