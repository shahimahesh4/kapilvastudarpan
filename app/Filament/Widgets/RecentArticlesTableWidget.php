<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentArticlesTableWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Recent Articles')
            ->description('List of recently created and edited articles')
            ->query(
                fn (): Builder => Article::query()
                    ->when(auth()->user()?->isPublisher(), fn ($q) => $q->where('user_id', auth()->id()))
                    ->orderByRaw('COALESCE(published_at, created_at) DESC')
            )
            ->defaultPaginationPageOption(6)
            ->paginated([6, 12, 24])
            ->headerActions([
                Action::make('create')
                    ->label('Add New Article')
                    ->icon('heroicon-m-plus')
                    ->url('/stnapanel/articles/create')
                    ->button(),

                Action::make('all_articles')
                    ->label('View All Articles')
                    ->icon('heroicon-m-arrow-right')
                    ->url('/stnapanel/articles')
                    ->color('gray'),
            ])
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Image')
                    ->circular()
                    ->state(fn (Article $record): string => $record->image_url)
                    ->defaultImageUrl(asset('images/favicon.png')),

                TextColumn::make('title')
                    ->label('Title')
                    ->weight('bold')
                    ->wrap()
                    ->limit(65)
                    ->url(fn (Article $record): string => '/stnapanel/articles/' . $record->id . '/edit'),

                TextColumn::make('categories.name')
                    ->label('Category')
                    ->badge()
                    ->color('danger'),

                TextColumn::make('views')
                    ->label('Views')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state) => number_format((int) $state)),

                TextColumn::make('is_published')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Published' : 'Draft')
                    ->color(fn (bool $state): string => $state ? 'success' : 'warning'),

                TextColumn::make('published_at')
                    ->label('Date')
                    ->formatStateUsing(fn ($state) => $state ? $state->format('M d, Y') : '-'),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('edit')
                        ->label('Edit')
                        ->icon('heroicon-o-pencil-square')
                        ->url(fn (Article $record): string => '/stnapanel/articles/' . $record->id . '/edit'),

                    Action::make('view_online')
                        ->label('View')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->color('gray')
                        ->visible(fn (Article $record): bool => ! empty($record->slug))
                        ->url(fn (Article $record): string => route('articles.show', $record->slug))
                        ->openUrlInNewTab(),

                    Action::make('publish')
                        ->label('Publish')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Article $record): bool => ! $record->is_published)
                        ->requiresConfirmation()
                        ->modalHeading('Publish Article')
                        ->modalDescription('Are you sure you want to publish this article?')
                        ->action(fn (Article $record) => $record->publish()),

                    Action::make('unpublish')
                        ->label('Unpublish')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->visible(fn (Article $record): bool => (bool) $record->is_published)
                        ->requiresConfirmation()
                        ->modalHeading('Unpublish Article')
                        ->modalDescription('Are you sure you want to unpublish this article?')
                        ->action(fn (Article $record) => $record->unpublish()),

                    DeleteAction::make()
                        ->label('Delete')
                        ->icon('heroicon-o-trash')
                        ->modalHeading('Delete Article')
                        ->modalDescription('Are you sure you want to delete this article?'),
                ])
                ->icon('heroicon-m-ellipsis-vertical')
                ->tooltip('Options'),
            ]);
    }
}
