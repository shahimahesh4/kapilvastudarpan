<?php

namespace App\Filament\Widgets;

use App\Models\Comment;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentCommentsTableWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = auth()->user();
        return $user && ($user->isAdmin() || $user->isEditor());
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Recent Comments')
            ->description('Reader comments on news articles and instant moderation')
            ->query(
                fn (): Builder => Comment::query()->with(['user', 'article'])->latest()
            )
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 20])
            ->headerActions([
                Action::make('all_comments')
                    ->label('View All Comments')
                    ->icon('heroicon-m-chat-bubble-left-right')
                    ->url('/stnapanel/comments')
                    ->color('gray'),
            ])
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('article.title')
                    ->label('Article Title')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->article?->title),

                TextColumn::make('content')
                    ->label('Comment')
                    ->limit(65)
                    ->wrap(),

                IconColumn::make('is_approved')
                    ->label('Approved')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M d, Y h:i A'),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Comment $record): bool => ! $record->is_approved)
                        ->action(fn (Comment $record) => $record->update(['is_approved' => true])),

                    Action::make('unapprove')
                        ->label('Unapprove')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->visible(fn (Comment $record): bool => (bool) $record->is_approved)
                        ->action(fn (Comment $record) => $record->update(['is_approved' => false])),

                    Action::make('view_article')
                        ->label('View Article')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->color('gray')
                        ->url(fn (Comment $record): string => $record->article ? article_url($record->article) : '#')
                        ->openUrlInNewTab()
                        ->visible(fn (Comment $record): bool => ! empty($record->article?->slug)),

                    DeleteAction::make()
                        ->label('Delete')
                        ->modalHeading('Delete Comment')
                        ->modalDescription('Are you sure you want to delete this comment?'),
                ])
                ->icon('heroicon-m-ellipsis-vertical')
                ->tooltip('Options'),
            ]);
    }
}
