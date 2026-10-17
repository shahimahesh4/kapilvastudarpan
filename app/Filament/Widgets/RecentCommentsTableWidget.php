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

    public function table(Table $table): Table
    {
        return $table
            ->heading('ताजा पाठक प्रतिक्रियाहरू (Recent Comments)')
            ->description('पाठकहरूले समाचारमा गरेका टिप्पणीहरू र तत्काल स्वीकृति नियन्त्रण')
            ->query(
                fn (): Builder => Comment::query()->with(['user', 'article'])->latest()
            )
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 20])
            ->headerActions([
                Action::make('all_comments')
                    ->label('सबै प्रतिक्रियाहरू हेर्नुहोस्')
                    ->icon('heroicon-m-chat-bubble-left-right')
                    ->url('/admin/comments')
                    ->color('gray'),
            ])
            ->columns([
                TextColumn::make('user.name')
                    ->label('प्रयोगकर्ता')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('article.title')
                    ->label('समाचार शीर्षक')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->article?->title),

                TextColumn::make('content')
                    ->label('प्रतिक्रिया')
                    ->limit(65)
                    ->wrap(),

                IconColumn::make('is_approved')
                    ->label('स्वीकृत')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('मिति')
                    ->dateTime('M d, Y h:i A'),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('approve')
                        ->label('Approve (स्वीकृत गर्नुहोस्)')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Comment $record): bool => ! $record->is_approved)
                        ->action(fn (Comment $record) => $record->update(['is_approved' => true])),

                    Action::make('unapprove')
                        ->label('Unapprove (अस्वीकृत गर्नुहोस्)')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->visible(fn (Comment $record): bool => (bool) $record->is_approved)
                        ->action(fn (Comment $record) => $record->update(['is_approved' => false])),

                    Action::make('view_article')
                        ->label('View Article (समाचार हेर्नुहोस्)')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->color('gray')
                        ->url(fn (Comment $record): string => $record->article ? route('articles.show', $record->article->slug) : '#')
                        ->openUrlInNewTab()
                        ->visible(fn (Comment $record): bool => ! empty($record->article?->slug)),

                    DeleteAction::make()
                        ->label('Delete (मेटाउनुहोस्)')
                        ->modalHeading('Delete Comment')
                        ->modalDescription('के तपाईं यो प्रतिक्रिया मेटाउन निश्चित हुनुहुन्छ?'),
                ]),
            ]);
    }
}
