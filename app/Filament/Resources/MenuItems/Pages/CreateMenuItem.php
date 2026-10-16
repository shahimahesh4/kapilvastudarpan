<?php

namespace App\Filament\Resources\MenuItems\Pages;

use App\Filament\Resources\MenuItems\MenuItemResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateMenuItem extends CreateRecord
{
    protected static string $resource = MenuItemResource::class;

    protected Width | string | null $maxContentWidth = Width::Full;

    public function getMaxContentWidth(): Width | string | null
    {
        return Width::Full;
    }
}
