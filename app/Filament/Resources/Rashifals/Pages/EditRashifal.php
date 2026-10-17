<?php

namespace App\Filament\Resources\Rashifals\Pages;

use App\Filament\Resources\Rashifals\RashifalResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRashifal extends EditRecord
{
    protected static string $resource = RashifalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
