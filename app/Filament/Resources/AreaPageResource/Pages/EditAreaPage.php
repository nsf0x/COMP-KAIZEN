<?php

namespace App\Filament\Resources\AreaPageResource\Pages;

use App\Filament\Resources\AreaPageResource;
use App\Filament\Resources\Pages\Concerns\HasResourceIndexNavigation;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAreaPage extends EditRecord
{
    use HasResourceIndexNavigation;

    protected static string $resource = AreaPageResource::class;

    protected function getHeaderActions(): array
    {
        return [$this->getBackToIndexAction()];
    }
}
