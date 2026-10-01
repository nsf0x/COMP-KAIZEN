<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\Pages\Concerns\HasResourceIndexNavigation;
use App\Filament\Resources\ProductResource;
use App\Models\Category;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateProduct extends CreateRecord
{
    use HasResourceIndexNavigation;

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [$this->getBackToIndexAction()];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $customCategory = trim((string) ($data['custom_category'] ?? ''));

        if ($customCategory !== '') {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($customCategory)],
                [
                    'name' => $customCategory,
                    'type' => 'produk',
                    'icon' => 'tag',
                    'description' => null,
                    'order' => (Category::max('order') ?? 0) + 1,
                    'is_active' => true,
                ]
            );

            $data['category_id'] = $category->id;
        }

        unset($data['custom_category']);

        return $data;
    }
}
