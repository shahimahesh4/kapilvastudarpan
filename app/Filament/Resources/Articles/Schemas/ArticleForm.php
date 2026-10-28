<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Models\Article;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'lg' => 3])
            ->components([
                // -------------------------------------------------------------
                // LEFT COLUMN (Main Content Area - 2/3 width)
                // -------------------------------------------------------------
                Group::make([
                    Section::make('Article Information')
                        ->schema([
                            TextInput::make('title')
                                ->label('Article Title')
                                ->required()
                                ->placeholder('Enter article title / headline...')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (?string $state, callable $set, callable $get) {
                                    if (blank($get('meta_title'))) {
                                        $set('meta_title', Article::suggestedMetaTitle($state));
                                    }

                                    if (blank($get('meta_keywords'))) {
                                        $set('meta_keywords', Article::suggestedMetaKeywords($state));
                                    }
                                })
                                ->columnSpanFull(),

                            RichEditor::make('body')
                                ->label('Content')
                                ->hint(new \Illuminate\Support\HtmlString('<a href="/stnapanel/nepali-unicode-converter" target="_blank" class="text-xs text-stone-500 hover:text-stone-800 dark:text-stone-400 font-bold ml-2" title="नेपाली युनिकोड कन्भर्टर">⌨️ युनिकोड</a>'))
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (?string $state, callable $set, callable $get) {
                                    if (blank($get('meta_description'))) {
                                        $set('meta_description', Article::suggestedMetaDescription($get('excerpt'), $state, $get('title')));
                                    }
                                })
                                ->minHeight('450px')
                                ->toolbarButtons([
                                    'attachFiles',
                                    'blockquote',
                                    'bold',
                                    'bulletList',
                                    'codeBlock',
                                    'h2',
                                    'h3',
                                    'italic',
                                    'link',
                                    'orderedList',
                                    'redo',
                                    'strike',
                                    'underline',
                                    'undo',
                                ])
                                ->columnSpanFull(),

                            Textarea::make('excerpt')
                                ->label('Short Summary (Excerpt)')
                                ->rows(3)
                                ->placeholder('Brief summary of the article to be shown in previews and search results...')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (?string $state, callable $set, callable $get) {
                                    if (blank($get('meta_description'))) {
                                        $set('meta_description', Article::suggestedMetaDescription($state, $get('body'), $get('title')));
                                    }
                                })
                                ->columnSpanFull(),
                        ]),

                    Section::make('Featured Image')
                        ->schema([
                            FileUpload::make('image_path')
                                ->label('Upload Image')
                                ->image()
                                ->disk('public')
                                ->directory('articles')
                                ->imageEditor()
                                ->maxSize(5120)
                                ->helperText('Upload an image from your computer (JPG, PNG, WebP up to 5MB) or drag & drop.')
                                ->dehydrateStateUsing(function ($state, $record, callable $get) {
                                    $customUrl = $get('custom_image_url');
                                    if (! empty($customUrl)) {
                                        return $customUrl;
                                    }
                                    return $state ?: $record?->image_path;
                                }),

                            TextInput::make('custom_image_url')
                                ->label('Or External Image URL')
                                ->placeholder('https://images.unsplash.com/... or https://...')
                                ->helperText('Optional: Provide an external image URL if you do not wish to upload a file.')
                                ->dehydrated(false)
                                ->afterStateHydrated(function (TextInput $component, $state, $record) {
                                    if ($record && str_starts_with((string) $record->image_path, 'http')) {
                                        $component->state($record->image_path);
                                    }
                                }),
                        ]),
                ])->columnSpan(['default' => 1, 'lg' => 2]),

                // -------------------------------------------------------------
                // RIGHT COLUMN (Sidebar Area - 1/3 width)
                // -------------------------------------------------------------
                Group::make([
                    Section::make('Publishing & Status')
                        ->schema([
                            Toggle::make('is_published')
                                ->label('Published')
                                ->default(true)
                                ->onColor('success')
                                ->offColor('gray')
                                ->helperText('Toggle off to unpublish or save as draft (hidden from readers).')
                                ->live()
                                ->afterStateUpdated(function (bool $state, callable $set, callable $get) {
                                    if ($state && blank($get('published_at'))) {
                                        $set('published_at', now());
                                    }
                                }),

                            DateTimePicker::make('published_at')
                                ->label('Publish Date')
                                ->default(now())
                                ->helperText('Scheduled date and time when the article goes live.'),

                            Toggle::make('is_featured')
                                ->label('Featured on Homepage')
                                ->helperText('Highlight in top headline banners'),

                            Toggle::make('is_breaking')
                                ->label('Breaking News')
                                ->helperText('Show in top breaking news ticker and alert bar'),
                        ]),

                    Section::make('Associations')
                        ->schema([
                            Select::make('categories')
                                ->label('Categories')
                                ->relationship('categories', 'name')
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->required()
                                ->helperText('Select one or more categories for this article.'),

                            Select::make('province')
                                ->label('Province')
                                ->options([
                                    'कोशी प्रदेश' => 'Koshi Province',
                                    'मधेश प्रदेश' => 'Madhesh Province',
                                    'बागमती प्रदेश' => 'Bagmati Province',
                                    'गण्डकी प्रदेश' => 'Gandaki Province',
                                    'लुम्बिनी प्रदेश' => 'Lumbini Province',
                                    'कर्णाली प्रदेश' => 'Karnali Province',
                                    'सुदूरपश्चिम प्रदेश' => 'Sudurpashchim Province',
                                ])
                                ->searchable(),

                            TextInput::make('author')
                                ->label('Author / Reporter')
                                ->required()
                                ->default('कपिलवस्तु दर्पण'),
                        ]),

                    Section::make('SEO & Source Link')
                        ->collapsed()
                        ->schema([
                            TextInput::make('source_url')
                                ->label('Original Source URL')
                                ->url()
                                ->placeholder('https://...'),

                            TextInput::make('meta_title')
                                ->label('SEO Meta Title')
                                ->placeholder('Automatically generated from the article title')
                                ->helperText('Generated automatically; you can edit it if needed.'),

                            Textarea::make('meta_description')
                                ->label('Meta Description')
                                ->rows(2)
                                ->placeholder('Automatically generated from the summary or article content')
                                ->helperText('Generated automatically; you can edit it if needed.'),

                            TextInput::make('meta_keywords')
                                ->label('Meta Keywords')
                                ->placeholder('Automatically generated from the article title')
                                ->helperText('Generated automatically; you can edit it if needed.'),
                        ]),
                ])->columnSpan(['default' => 1, 'lg' => 1]),
            ]);
    }
}
