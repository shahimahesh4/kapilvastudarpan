<?php

namespace App\Filament\Resources\MenuItems;

use App\Filament\Resources\MenuItems\Pages\CreateMenuItem;
use App\Filament\Resources\MenuItems\Pages\EditMenuItem;
use App\Filament\Resources\MenuItems\Pages\ListMenuItems;
use App\Models\MenuItem;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBars3;

    protected static ?string $navigationLabel = 'Menus';

    protected static ?string $modelLabel = 'Menu';

    protected static ?string $pluralModelLabel = 'Menus';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Menu Item Details')
                    ->columnSpanFull()
                    ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('title')
                            ->label('Menu Title')
                            ->required()
                            ->placeholder('e.g. Main News, Politics, e-Paper'),

                        Select::make('location')
                            ->label('Location')
                            ->options(MenuItem::LOCATIONS)
                            ->default('header')
                            ->required(),
                    ]),

                    Grid::make(2)->schema([
                        Select::make('type')
                            ->label('Link Type')
                            ->options(MenuItem::TYPES)
                            ->default('category')
                            ->required()
                            ->live(),

                        Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->visible(fn ($get) => $get('type') === 'category'),

                        Select::make('page_id')
                            ->label('Page')
                            ->relationship('page', 'title')
                            ->searchable()
                            ->preload()
                            ->visible(fn ($get) => $get('type') === 'page'),

                        TextInput::make('url')
                            ->label('Custom URL')
                            ->placeholder('https://... or /custom-url')
                            ->visible(fn ($get) => $get('type') === 'custom'),
                    ]),

                    Grid::make(3)->schema([
                        Select::make('parent_id')
                            ->label('Parent Menu (Dropdown)')
                            ->options(fn (?MenuItem $record) => MenuItem::whereNull('parent_id')
                                ->when($record?->id, fn ($q) => $q->where('id', '!=', $record->id))
                                ->pluck('title', 'id'))
                            ->searchable()
                            ->placeholder('No Parent (Main Menu)'),

                        Select::make('target')
                            ->label('Target')
                            ->options(MenuItem::TARGETS)
                            ->default('_self')
                            ->required(),

                        TextInput::make('order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                    ]),

                    Grid::make(2)->schema([
                        Toggle::make('is_mega_menu')
                            ->label('Mega Menu (Articles Grid)')
                            ->helperText('Show featured news articles dropdown under this menu item')
                            ->default(false),

                        Toggle::make('is_active')
                            ->label('Active in Navigation')
                            ->default(true),
                    ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order')
                    ->label('Order')
                    ->sortable()
                    ->badge(),

                TextColumn::make('title')
                    ->label('Menu Title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('parent.title')
                    ->label('Parent')
                    ->placeholder('Main Menu')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'category' => 'Category',
                        'page' => 'Page',
                        default => 'Custom URL',
                    })
                    ->color(fn ($state) => match ($state) {
                        'category' => 'success',
                        'page' => 'info',
                        default => 'warning',
                    }),

                TextColumn::make('children_count')
                    ->counts('allChildren')
                    ->label('Sub-items')
                    ->badge(),

                IconColumn::make('is_mega_menu')
                    ->label('Mega Menu')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->filters([
                SelectFilter::make('location')
                    ->label('Location')
                    ->options(MenuItem::LOCATIONS),

                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    Action::make('toggle_active')
                        ->label(fn (MenuItem $record): string => $record->is_active ? 'Deactivate' : 'Activate')
                        ->icon(fn (MenuItem $record): string => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                        ->color(fn (MenuItem $record): string => $record->is_active ? 'warning' : 'success')
                        ->action(fn (MenuItem $record) => $record->update(['is_active' => ! $record->is_active])),
                    Action::make('visit_link')
                        ->label('Visit Link')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->color('gray')
                        ->url(fn (MenuItem $record): string => $record->link_url)
                        ->openUrlInNewTab()
                        ->visible(fn (MenuItem $record): bool => $record->link_url !== '#'),
                    DeleteAction::make()
                        ->modalHeading('Delete Menu Item')
                        ->modalDescription('Are you sure you want to delete this menu item?'),
                ])
                ->icon('heroicon-m-ellipsis-vertical')
                ->tooltip('Options'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenuItems::route('/'),
            'create' => CreateMenuItem::route('/create'),
            'edit' => EditMenuItem::route('/{record}/edit'),
        ];
    }
}
