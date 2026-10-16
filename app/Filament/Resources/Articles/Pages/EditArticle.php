<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Delete'),
            RestoreAction::make()
                ->label('Restore'),
            ForceDeleteAction::make()
                ->label('Delete Permanently'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! empty($data['categories']) && is_array($data['categories'])) {
            $data['category_id'] = $data['categories'][0];
        }

        return $data;
    }

    protected function afterSave(): void
    {
        if (empty($this->record->category_id)) {
            $firstCategory = $this->record->categories()->first();
            if ($firstCategory) {
                $this->record->updateQuietly(['category_id' => $firstCategory->id]);
            }
        }
    }
}
