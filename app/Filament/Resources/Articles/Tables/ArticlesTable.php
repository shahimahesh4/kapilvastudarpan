<?php

namespace App\Filament\Resources\Articles\Tables;

use App\Models\Article;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(60),

                TextColumn::make('categories.name')
                    ->label('Categories')
                    ->badge()
                    ->color('danger'),

                ToggleColumn::make('is_published')
                    ->label('Published')
                    ->sortable(),

                TextColumn::make('province')
                    ->label('Province')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn (?string $state): ?string => match ($state) {
                        'कोशी प्रदेश' => 'Koshi Province',
                        'मधेश प्रदेश' => 'Madhesh Province',
                        'बागमती प्रदेश' => 'Bagmati Province',
                        'गण्डकी प्रदेश' => 'Gandaki Province',
                        'लुम्बिनी प्रदेश' => 'Lumbini Province',
                        'कर्णाली प्रदेश' => 'Karnali Province',
                        'सुदूरपश्चिम प्रदेश' => 'Sudurpashchim Province',
                        default => $state,
                    })
                    ->toggleable(),

                TextColumn::make('author')
                    ->label('Author')
                    ->toggleable(),

                TextColumn::make('views')
                    ->label('Views')
                    ->sortable()
                    ->badge(),

                IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured'),

                IconColumn::make('is_breaking')
                    ->boolean()
                    ->label('Breaking'),

                TextColumn::make('published_at')
                    ->label('Published Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->defaultPaginationPageOption(30)
            ->paginationPageOptions([10, 25, 30, 50, 100])
            ->filters([
                SelectFilter::make('is_published')
                    ->label('Publication Status')
                    ->options([
                        '1' => 'Published',
                        '0' => 'Unpublished / Draft',
                    ]),

                SelectFilter::make('categories')
                    ->relationship('categories', 'name')
                    ->label('Category')
                    ->preload(),

                SelectFilter::make('province')
                    ->options([
                        'कोशी प्रदेश' => 'Koshi Province',
                        'मधेश प्रदेश' => 'Madhesh Province',
                        'बागमती प्रदेश' => 'Bagmati Province',
                        'गण्डकी प्रदेश' => 'Gandaki Province',
                        'लुम्बिनी प्रदेश' => 'Lumbini Province',
                        'कर्णाली प्रदेश' => 'Karnali Province',
                        'सुदूरपश्चिम प्रदेश' => 'Sudurpashchim Province',
                    ])
                    ->label('Province'),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->visible(fn (Article $record): bool => ! $record->trashed()),

                    Action::make('publish')
                        ->label('Publish')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Article $record): bool => ! $record->is_published && ! $record->trashed())
                        ->requiresConfirmation()
                        ->modalHeading('Publish Article')
                        ->modalDescription('Are you sure you want to publish this article on the website?')
                        ->action(fn (Article $record) => $record->publish()),

                    Action::make('unpublish')
                        ->label('Unpublish')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->visible(fn (Article $record): bool => (bool) $record->is_published && ! $record->trashed())
                        ->requiresConfirmation()
                        ->modalHeading('Unpublish Article')
                        ->modalDescription('Are you sure you want to unpublish this article? Readers will no longer see it.')
                        ->action(fn (Article $record) => $record->unpublish()),

                    DeleteAction::make()
                        ->label('Delete')
                        ->icon('heroicon-o-trash')
                        ->modalHeading('Delete Article')
                        ->modalDescription('Are you sure you want to delete this article? It will be moved to trash and can be restored later.'),

                    RestoreAction::make()
                        ->label('Restore')
                        ->icon('heroicon-o-arrow-uturn-left')
                        ->modalHeading('Restore Article')
                        ->modalDescription('Are you sure you want to restore this article from trash?'),

                    ForceDeleteAction::make()
                        ->label('Delete Permanently')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (Article $record): bool => $record->trashed())
                        ->modalHeading('Permanently Delete Article')
                        ->modalDescription('This action cannot be undone. The article will be deleted forever.'),
                ])
                ->icon('heroicon-m-ellipsis-vertical')
                ->tooltip('Options'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('publishSelected')
                        ->label('Publish Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->publish())
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('unpublishSelected')
                        ->label('Unpublish Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->unpublish())
                        ->deselectRecordsAfterCompletion(),

                    DeleteBulkAction::make()
                        ->label('Delete Selected'),

                    RestoreBulkAction::make()
                        ->label('Restore Selected'),

                    ForceDeleteBulkAction::make()
                        ->label('Delete Permanently'),
                ]),
            ]);
    }
}
