<?php

namespace App\Filament\Resources\Advertisements;

use App\Filament\Resources\Advertisements\Pages\CreateAdvertisement;
use App\Filament\Resources\Advertisements\Pages\EditAdvertisement;
use App\Filament\Resources\Advertisements\Pages\ListAdvertisements;
use App\Models\Advertisement;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AdvertisementResource extends Resource
{
    protected static ?string $model = Advertisement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static ?string $navigationLabel = 'Advertisements';

    protected static ?string $modelLabel = 'Advertisement';

    protected static ?string $pluralModelLabel = 'Advertisements';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Banner Advertisement Details')
                    ->columnSpanFull()
                    ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('title')
                            ->label('Title / Company Name')
                            ->required()
                            ->placeholder('e.g. Tata Motors Festival Offer'),

                        Select::make('placement')
                            ->label('Placement Slot')
                            ->options(Advertisement::PLACEMENTS)
                            ->required()
                            ->searchable(),

                        Select::make('type')
                            ->label('Type')
                            ->options([
                                'image' => 'Image Banner',
                                'code' => 'Custom Embed Code (HTML/JS/AdSense)',
                            ])
                            ->default('image')
                            ->required(),

                        TextInput::make('order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                    ]),

                    Grid::make(2)->schema([
                        FileUpload::make('image_path')
                            ->label('Banner Ad Image (विज्ञापन फोटो अपलोड)')
                            ->image()
                            ->disk('public')
                            ->directory('advertisements')
                            ->maxSize(5120)
                            ->helperText('Upload banner ad image (PNG, JPG, GIF, WebP).'),

                        TextInput::make('target_url')
                            ->label('Click Target URL')
                            ->url()
                            ->placeholder('https://www.example.com'),
                    ]),

                    Textarea::make('code_snippet')
                        ->label('Custom Embed Code (HTML / Google AdSense)')
                        ->rows(5)
                        ->placeholder('<ins class="adsbygoogle" ...></ins>')
                        ->columnSpanFull(),

                    Grid::make(3)->schema([
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                        DateTimePicker::make('starts_at')
                            ->label('Start Date'),

                        DateTimePicker::make('ends_at')
                            ->label('End Date'),
                    ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('placement')
                    ->label('Placement')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'header' => 'Header',
                        'home_top' => 'Home Top',
                        'home_middle' => 'Home Middle',
                        'sidebar' => 'Sidebar',
                        'article_incontent' => 'In-content',
                        'footer' => 'Footer',
                        default => $state,
                    })
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'code' ? 'HTML Code' : 'Image')
                    ->color(fn ($state) => $state === 'code' ? 'warning' : 'info'),

                TextColumn::make('impressions')
                    ->label('Views')
                    ->sortable()
                    ->badge(),

                TextColumn::make('clicks')
                    ->label('Clicks')
                    ->sortable()
                    ->badge()
                    ->color('success'),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('ends_at')
                    ->label('Expiry')
                    ->dateTime()
                    ->placeholder('Always Active')
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->filters([
                SelectFilter::make('placement')
                    ->label('Placement')
                    ->options(Advertisement::PLACEMENTS),

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
                        ->label(fn (Advertisement $record): string => $record->is_active ? 'Deactivate' : 'Activate')
                        ->icon(fn (Advertisement $record): string => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                        ->color(fn (Advertisement $record): string => $record->is_active ? 'warning' : 'success')
                        ->action(fn (Advertisement $record) => $record->update(['is_active' => ! $record->is_active])),
                    Action::make('visit_url')
                        ->label('Visit Target URL')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->color('gray')
                        ->url(fn (Advertisement $record): ?string => $record->target_url)
                        ->openUrlInNewTab()
                        ->visible(fn (Advertisement $record): bool => ! empty($record->target_url)),
                    DeleteAction::make()
                        ->modalHeading('Delete Advertisement')
                        ->modalDescription('Are you sure you want to delete this advertisement?'),
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
            'index' => ListAdvertisements::route('/'),
            'create' => CreateAdvertisement::route('/create'),
            'edit' => EditAdvertisement::route('/{record}/edit'),
        ];
    }
}
