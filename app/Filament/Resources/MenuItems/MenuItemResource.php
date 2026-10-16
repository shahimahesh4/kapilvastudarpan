<?php

namespace App\Filament\Resources\MenuItems;

use App\Filament\Resources\MenuItems\Pages\CreateMenuItem;
use App\Filament\Resources\MenuItems\Pages\EditMenuItem;
use App\Filament\Resources\MenuItems\Pages\ListMenuItems;
use App\Models\MenuItem;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
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
                    ->formatStateUsing(fn ($state) => $state === 'category' ? 'Category' : 'Custom URL')
                    ->color(fn ($state) => $state === 'category' ? 'success' : 'warning'),

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
                EditAction::make(),
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
