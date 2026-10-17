<?php

namespace App\Filament\Resources\Pages\Tables;

use App\Models\Page;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order')
                    ->label('Order')
                    ->sortable()
                    ->badge(),

                TextColumn::make('title')
                    ->label('Page Title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Page $record): ?string => $record->excerpt ? \Illuminate\Support\Str::limit($record->excerpt, 50) : null),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('template')
                    ->label('Template')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'default' => 'Default',
                        'contact' => 'Contact',
                        'team' => 'Our Team',
                        'full_width' => 'Full Width',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'contact' => 'info',
                        'team' => 'success',
                        'full_width' => 'warning',
                        default => 'primary',
                    }),

                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('views')
                    ->label('Views')
                    ->sortable()
                    ->badge()
                    ->color('secondary'),

                TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime('M d, Y')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order')
            ->filters([
                SelectFilter::make('is_published')
                    ->label('Status')
                    ->options([
                        '1' => 'Published',
                        '0' => 'Draft',
                    ]),

                SelectFilter::make('template')
                    ->label('Template')
                    ->options([
                        'default' => 'Default',
                        'contact' => 'Contact',
                        'team' => 'Our Team',
                        'full_width' => 'Full Width',
                    ]),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->visible(fn (Page $record): bool => ! $record->trashed()),

                    Action::make('publish')
                        ->label('Publish')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Page $record): bool => ! $record->is_published && ! $record->trashed())
                        ->requiresConfirmation()
                        ->modalHeading('Publish Page')
                        ->modalDescription('Are you sure you want to publish this page on the website?')
                        ->action(fn (Page $record) => $record->publish()),

                    Action::make('unpublish')
                        ->label('Unpublish')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->visible(fn (Page $record): bool => (bool) $record->is_published && ! $record->trashed())
                        ->requiresConfirmation()
                        ->modalHeading('Unpublish Page')
                        ->modalDescription('Are you sure you want to unpublish this page? Readers will no longer see it.')
                        ->action(fn (Page $record) => $record->unpublish()),

                    Action::make('view_page')
                        ->label('View')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->color('gray')
                        ->url(fn (Page $record): string => route('pages.show', $record->slug))
                        ->openUrlInNewTab()
                        ->visible(fn (Page $record): bool => ! $record->trashed()),

                    DeleteAction::make()
                        ->label('Delete')
                        ->icon('heroicon-o-trash')
                        ->modalHeading('Delete Page')
                        ->modalDescription('Are you sure you want to delete this page? It will be moved to trash and can be restored later.'),

                    RestoreAction::make()
                        ->label('Restore')
                        ->icon('heroicon-o-arrow-uturn-left')
                        ->modalHeading('Restore Page')
                        ->modalDescription('Are you sure you want to restore this page from trash?'),

                    ForceDeleteAction::make()
                        ->label('Delete Permanently')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (Page $record): bool => $record->trashed())
                        ->modalHeading('Permanently Delete Page')
                        ->modalDescription('This action cannot be undone. The page will be deleted forever.'),
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
