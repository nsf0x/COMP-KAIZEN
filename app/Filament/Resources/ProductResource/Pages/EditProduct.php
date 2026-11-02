<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\Pages\Concerns\HasResourceIndexNavigation;
use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    use HasResourceIndexNavigation;

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getBackToIndexAction(),
            Actions\DeleteAction::make(),
        ];
    }
}
