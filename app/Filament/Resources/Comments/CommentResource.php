<?php

namespace App\Filament\Resources\Comments;

use App\Filament\Resources\Comments\Pages\EditComment;
use App\Filament\Resources\Comments\Pages\ListComments;
use App\Models\Comment;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $navigationLabel = 'Comments';

    protected static ?string $modelLabel = 'Comment';

    protected static ?string $pluralModelLabel = 'Comments';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Comment Details')
                    ->schema([
                        Textarea::make('content')
                            ->label('Comment Text')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),

                        Toggle::make('is_approved')
                            ->label('Approved (स्वीकृत)')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('article.title')
                    ->label('Article')
                    ->searchable()
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->article?->title),

                TextColumn::make('content')
                    ->label('Comment')
                    ->limit(60)
                    ->searchable(),

                IconColumn::make('is_approved')
                    ->label('Approved')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
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

                    EditAction::make(),

                    Action::make('view_article')
                        ->label('View Article')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->color('gray')
                        ->url(fn (Comment $record): string => $record->article ? route('articles.show', $record->article->slug) : '#')
                        ->openUrlInNewTab()
                        ->visible(fn (Comment $record): bool => ! empty($record->article?->slug)),

                    DeleteAction::make()
                        ->modalHeading('Delete Comment')
                        ->modalDescription('Are you sure you want to delete this comment?'),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListComments::route('/'),
            'edit' => EditComment::route('/{record}/edit'),
        ];
    }
}
