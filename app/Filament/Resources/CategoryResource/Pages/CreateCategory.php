<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\Pages\Concerns\HasResourceIndexNavigation;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    use HasResourceIndexNavigation;

    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [$this->getBackToIndexAction()];
    }
}
