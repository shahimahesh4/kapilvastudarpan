<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! empty($data['categories']) && is_array($data['categories'])) {
            $data['category_id'] = $data['categories'][0];
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        if (empty($this->record->category_id)) {
            $firstCategory = $this->record->categories()->first();
            if ($firstCategory) {
                $this->record->updateQuietly(['category_id' => $firstCategory->id]);
            }
        }
    }
}
