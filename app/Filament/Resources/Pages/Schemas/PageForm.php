<?php

namespace App\Filament\Resources\Pages\Schemas;

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
use Illuminate\Support\Str;

class PageForm
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
                    Section::make('Page Information')
                        ->schema([
                            TextInput::make('title')
                                ->label('Page Title')
                                ->required()
                                ->placeholder('e.g. हाम्रो बारेमा (About Us), गोपनीयता नीति (Privacy Policy)...')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (string $operation, $state, callable $set, callable $get) {
                                    if ($operation === 'create' || blank($get('slug'))) {
                                        $slug = Str::slug((string) $state);
                                        $set('slug', $slug ?: 'page-' . time());
                                    }
                                })
                                ->columnSpanFull(),

                            TextInput::make('slug')
                                ->label('URL Slug')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->placeholder('about-us')
                                ->helperText('URL identifier for the page, e.g. /page/about-us')
                                ->columnSpanFull(),

                            RichEditor::make('body')
                                ->label('Page Content')
                                ->required()
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
                                ->label('Short Summary / Excerpt')
                                ->rows(3)
                                ->placeholder('Brief introductory summary of the page...')
                                ->columnSpanFull(),
                        ]),

                    Section::make('Banner / Featured Image')
                        ->schema([
                            FileUpload::make('image_path')
                                ->label('Upload Image')
                                ->image()
                                ->disk('public')
                                ->directory('pages')
                                ->imageEditor()
                                ->maxSize(5120)
                                ->helperText('Upload a banner image (JPG, PNG, WebP up to 5MB) or specify an external link below.')
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
                    Section::make('Publishing & Layout')
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
                                ->helperText('Scheduled date and time when the page goes live.'),

                            TextInput::make('order')
                                ->label('Sort Order')
                                ->numeric()
                                ->default(0)
                                ->helperText('Lower numbers appear first in listings.'),

                            Select::make('template')
                                ->label('Page Template')
                                ->options([
                                    'default' => 'Default (Editorial with Sidebar)',
                                    'contact' => 'Contact Page (With Contact Box)',
                                    'team' => 'Our Team',
                                    'full_width' => 'Full Width (No Sidebar)',
                                ])
                                ->default('default')
                                ->required(),
                        ]),

                    Section::make('SEO & Meta Data')
                        ->collapsed()
                        ->schema([
                            TextInput::make('meta_title')
                                ->label('SEO Meta Title')
                                ->placeholder('Custom Google search result title (defaults to page title)')
                                ->helperText('Leave empty to automatically use page title'),

                            Textarea::make('meta_description')
                                ->label('Meta Description')
                                ->rows(3)
                                ->placeholder('Short description for search engines (Google)...'),

                            TextInput::make('meta_keywords')
                                ->label('Meta Keywords')
                                ->placeholder('kapilvastu darpan, about us, contact, nepal'),
                        ]),
                ])->columnSpan(['default' => 1, 'lg' => 1]),
            ]);
    }
}
