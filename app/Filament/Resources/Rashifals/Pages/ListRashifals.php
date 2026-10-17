<?php

namespace App\Filament\Resources\Rashifals\Pages;

use App\Filament\Resources\Rashifals\RashifalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRashifals extends ListRecords
{
    protected static string $resource = RashifalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
