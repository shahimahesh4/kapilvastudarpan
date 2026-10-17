<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use Filament\Actions\Action;
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
            ->heading('ताजा समाचार सामग्री (Recent Articles)')
            ->description('भर्खरै थपिएका तथा सम्पादित समाचारहरूको सूची')
            ->query(
                fn (): Builder => Article::query()->orderByRaw('COALESCE(published_at, created_at) DESC')
            )
            ->defaultPaginationPageOption(6)
            ->paginated([6, 12, 24])
            ->headerActions([
                Action::make('create')
                    ->label('नयाँ समाचार थप्नुहोस्')
                    ->icon('heroicon-m-plus')
                    ->url('/admin/articles/create')
                    ->button(),

                Action::make('all_articles')
                    ->label('सबै समाचार हेर्नुहोस्')
                    ->icon('heroicon-m-arrow-right')
                    ->url('/admin/articles')
                    ->color('gray'),
            ])
            ->columns([
                ImageColumn::make('image_path')
                    ->label('तस्वीर')
                    ->circular()
                    ->state(fn (Article $record): string => $record->image_url)
                    ->defaultImageUrl(asset('images/favicon.png')),

                TextColumn::make('title')
                    ->label('शीर्षक')
                    ->weight('bold')
                    ->wrap()
                    ->limit(65)
                    ->url(fn (Article $record): string => '/admin/articles/' . $record->id . '/edit'),

                TextColumn::make('categories.name')
                    ->label('विधा')
                    ->badge()
                    ->color('danger'),

                TextColumn::make('views')
                    ->label('भ्युज')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state) => nepali_number(number_format((int) $state))),

                TextColumn::make('is_published')
                    ->label('स्थिति')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'प्रकाशित' : 'मस्यौदा')
                    ->color(fn (bool $state): string => $state ? 'success' : 'warning'),

                TextColumn::make('published_at')
                    ->label('मिति')
                    ->formatStateUsing(fn ($state) => $state ? nepali_date($state) : '-'),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label('सम्पादन')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (Article $record): string => '/admin/articles/' . $record->id . '/edit'),

                Action::make('view_online')
                    ->label('हेर्नुहोस्')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->color('gray')
                    ->visible(fn (Article $record): bool => ! empty($record->slug))
                    ->url(fn (Article $record): string => route('articles.show', $record->slug))
                    ->openUrlInNewTab(),
            ]);
    }
}
