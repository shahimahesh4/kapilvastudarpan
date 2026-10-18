<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class NepaliUnicodeConverter extends Page
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedLanguage;

    protected static ?string $navigationLabel = 'Unicode Converter';

    protected static ?string $title = 'Nepali Unicode Converter (नेपाली युनिकोड)';

    protected static ?int $navigationSort = 11;

    protected string $view = 'filament.pages.nepali-unicode-converter';
}
