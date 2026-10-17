<?php

namespace App\Filament\Resources\Rashifals;

use App\Filament\Resources\Rashifals\Pages\CreateRashifal;
use App\Filament\Resources\Rashifals\Pages\EditRashifal;
use App\Filament\Resources\Rashifals\Pages\ListRashifals;
use App\Models\Rashifal;
use BackedEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RashifalResource extends Resource
{
    protected static ?string $model = Rashifal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static ?string $navigationLabel = 'राशिफल (Rashifal)';

    protected static ?string $modelLabel = 'राशिफल';

    protected static ?string $pluralModelLabel = 'राशिफल (Horoscope)';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('राशिको आधारभूत विवरण (Zodiac Sign Information)')
                    ->description('राशिको नाम, चिह्नाक्षर, र आधारभूत जानकारी व्यवस्थापन गर्नुहोस्।')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('name')
                                ->label('राशिको नाम (Name)')
                                ->required()
                                ->placeholder('उदा: मेष'),

                            TextInput::make('full_name')
                                ->label('पूरा नाम (Full Name)')
                                ->placeholder('उदा: मेष राशि'),

                            TextInput::make('english_name')
                                ->label('English Name')
                                ->placeholder('e.g. Aries'),
                        ]),

                        Grid::make(3)->schema([
                            TextInput::make('slug')
                                ->label('Slug (अंग्रेजी संकेत)')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->placeholder('e.g. mesh')
                                ->helperText('Unique URL identifier for this rashi.'),

                            TextInput::make('order')
                                ->label('क्रम (Sort Order 0-11)')
                                ->numeric()
                                ->default(0)
                                ->helperText('मेष = ० देखि मीन = ११ सम्मको शास्त्रीय क्रम।'),

                            Toggle::make('is_active')
                                ->label('सक्रिय / प्रकाशित (Active)')
                                ->default(true)
                                ->helperText('अन गर्दा यो राशि पोर्टलमा देखिन्छ।'),
                        ]),

                        TextInput::make('letters')
                            ->label('राशिका नामाक्षरहरू (Letters)')
                            ->placeholder('उदा: चु, चे, चो, ला, लि, लु, ले, लो, अ')
                            ->helperText('यस राशिभित्र पर्ने नामका सुरुवाती अक्षरहरू।')
                            ->columnSpanFull(),
                    ]),

                Section::make('ज्योतिषीय गुण तथा शुभ तत्वहरू (Astrological Attributes)')
                    ->description('ग्रह, तत्व, शुभ अंक, रङ तथा शुभ दिशा निर्धारण गर्नुहोस्।')
                    ->collapsible()
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('ruler')
                                ->label('स्वामी ग्रह (Ruling Planet / Ruler)')
                                ->placeholder('उदा: मंगल (Mars)'),

                            TextInput::make('element')
                                ->label('तत्व (Element)')
                                ->placeholder('उदा: अग्नि (Fire) / जल (Water)'),

                            TextInput::make('lucky_day')
                                ->label('शुभ दिन (Lucky Day)')
                                ->placeholder('उदा: मंगलबार'),
                        ]),

                        Grid::make(3)->schema([
                            TextInput::make('lucky_number')
                                ->label('शुभ अंक (Lucky Numbers)')
                                ->placeholder('उदा: १, ९'),

                            TextInput::make('lucky_color')
                                ->label('शुभ रङ (Lucky Colors)')
                                ->placeholder('उदा: रातो, पहेंलो'),

                            TextInput::make('lucky_direction')
                                ->label('शुभ दिशा (Lucky Direction)')
                                ->placeholder('उदा: पूर्व / उत्तर'),
                        ]),
                    ]),

                Section::make('फलादेश व्यवस्थापन (Horoscope Predictions)')
                    ->description('दैनिक, साप्ताहिक, मासिक तथा वार्षिक फलादेश प्रविष्ट वा सम्पादन गर्नुहोस्।')
                    ->columnSpanFull()
                    ->schema([
                        Tabs::make('PredictionsTabs')
                            ->tabs([
                                Tab::make('DainikTab')
                                    ->label('दैनिक राशिफल (Daily)')
                                    ->icon('heroicon-o-sun')
                                    ->schema([
                                        Textarea::make('dainik')
                                            ->label('आजको दैनिक फलादेश (Daily Prediction)')
                                            ->rows(5)
                                            ->placeholder('आजको दिनको विस्तृत राशिफल यहाँ लेख्नुहोस्...')
                                            ->helperText('गृहपृष्ठ तथा दैनिक ट्याबमा प्रदर्शन हुनेछ।')
                                            ->columnSpanFull(),
                                    ]),

                                Tab::make('SaptahikTab')
                                    ->label('साप्ताहिक राशिफल (Weekly)')
                                    ->icon('heroicon-o-calendar')
                                    ->schema([
                                        Textarea::make('saptahik')
                                            ->label('साप्ताहिक फलादेश (Weekly Prediction)')
                                            ->rows(5)
                                            ->placeholder('यस साताको राशिफल यहाँ लेख्नुहोस्...')
                                            ->helperText('साप्ताहिक ट्याबमा प्रदर्शन हुनेछ।')
                                            ->columnSpanFull(),
                                    ]),

                                Tab::make('MasikTab')
                                    ->label('मासिक राशिफल (Monthly)')
                                    ->icon('heroicon-o-calendar-days')
                                    ->schema([
                                        Textarea::make('masik')
                                            ->label('मासिक फलादेश (Monthly Prediction)')
                                            ->rows(5)
                                            ->placeholder('यस महिनाको राशिफल यहाँ लेख्नुहोस्...')
                                            ->helperText('मासिक ट्याबमा प्रदर्शन हुनेछ।')
                                            ->columnSpanFull(),
                                    ]),

                                Tab::make('VarshikTab')
                                    ->label('वार्षिक राशिफल (Yearly)')
                                    ->icon('heroicon-o-globe-americas')
                                    ->schema([
                                        Textarea::make('varshik')
                                            ->label('वार्षिक फलादेश (Yearly Prediction)')
                                            ->rows(9)
                                            ->placeholder('यस वर्षको विस्तृत वार्षिक फलादेश यहाँ लेख्नुहोस्...')
                                            ->helperText('वार्षिक राशिफल ट्याबमा विस्तृत रूपमा प्रदर्शन हुनेछ।')
                                            ->columnSpanFull(),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order')
                    ->label('#')
                    ->sortable()
                    ->width('50px'),

                TextColumn::make('name')
                    ->label('राशि')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Rashifal $record): string => $record->letters ?? ''),

                TextColumn::make('english_name')
                    ->label('English Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('ruler')
                    ->label('स्वामी ग्रह')
                    ->badge()
                    ->color('warning'),

                TextColumn::make('lucky_number')
                    ->label('शुभ अंक')
                    ->badge()
                    ->color('info'),

                TextColumn::make('lucky_color')
                    ->label('शुभ रङ'),

                IconColumn::make('is_active')
                    ->label('सक्रिय')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('अन्तिम अपडेट')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order', 'asc')
            ->defaultPaginationPageOption(25)
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRashifals::route('/'),
            'create' => CreateRashifal::route('/create'),
            'edit' => EditRashifal::route('/{record}/edit'),
        ];
    }
}
