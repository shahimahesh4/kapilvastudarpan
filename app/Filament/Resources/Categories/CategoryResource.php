<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Models\Category;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $navigationLabel = 'Categories';

    protected static ?string $modelLabel = 'Category';

    protected static ?string $pluralModelLabel = 'Categories';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user && ($user->isAdmin() || $user->isEditor());
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                \Filament\Schemas\Components\Section::make('Category Details')
                    ->columnSpanFull()
                    ->schema([
                    \Filament\Schemas\Components\Grid::make(2)->schema([
                        TextInput::make('name')
                            ->label('Category Name')
                            ->required()
                            ->placeholder('e.g. Politics, Sports, Economy')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, callable $set, callable $get) {
                                if ($operation === 'create' || blank($get('slug'))) {
                                    $slug = \Illuminate\Support\Str::slug((string) $state);
                                    $set('slug', $slug ?: 'category-' . time());
                                }
                            }),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->placeholder('e.g. politics, sports')
                            ->helperText('URL identifier for this category.'),
                    ]),

                    \Filament\Schemas\Components\Grid::make(2)->schema([
                        TextInput::make('order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers display first in navigation.'),

                        Toggle::make('is_nav')
                            ->label('Show in Navigation')
                            ->default(true)
                            ->helperText('Enable to display in the header menu.'),
                    ]),

                    Select::make('drawer_icon')
                        ->label('Mobile Menu Icon')
                        ->options(Category::DRAWER_ICONS)
                        ->placeholder('Automatic from category name')
                        ->helperText('Shown in the mobile navigation drawer. Leave blank to select an icon automatically from the category name.'),

                    Textarea::make('description')
                        ->label('Description')
                        ->rows(3)
                        ->placeholder('Brief description of this category...')
                        ->columnSpanFull(),
                ]),

                \Filament\Schemas\Components\Section::make('SEO & Meta Data')
                    ->collapsed()
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('SEO Meta Title')
                            ->placeholder('Custom Google search result title (defaults to category name)')
                            ->helperText('Leave empty to automatically use category name'),

                        Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(2)
                            ->placeholder('Short description for search engines...'),

                        TextInput::make('meta_keywords')
                            ->label('Meta Keywords')
                            ->placeholder('category keywords, news, nepal'),
                    ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order')
                    ->label('Order')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),

                TextColumn::make('drawer_icon')
                    ->label('Mobile Icon')
                    ->formatStateUsing(fn (?string $state): string => $state ? (Category::DRAWER_ICONS[$state] ?? $state) : 'Automatic')
                    ->toggleable(),

                IconColumn::make('is_nav')
                    ->label('In Menu')
                    ->boolean(),

                TextColumn::make('articles_count')
                    ->counts('articles')
                    ->label('Articles Count')
                    ->badge(),
            ])
            ->defaultSort('order')
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    Action::make('view_category')
                        ->label('View')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->color('gray')
                        ->url(fn (Category $record): string => route('category.show', $record->slug))
                        ->openUrlInNewTab(),
                    Action::make('toggle_nav')
                        ->label(fn (Category $record): string => $record->is_nav ? 'Hide from Nav' : 'Show in Nav')
                        ->icon(fn (Category $record): string => $record->is_nav ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                        ->color(fn (Category $record): string => $record->is_nav ? 'warning' : 'success')
                        ->action(fn (Category $record) => $record->update(['is_nav' => ! $record->is_nav])),
                    DeleteAction::make()
                        ->modalHeading('Delete Category')
                        ->modalDescription('Are you sure you want to delete this category?'),
                ])
                ->icon('heroicon-m-ellipsis-vertical')
                ->tooltip('Options'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
