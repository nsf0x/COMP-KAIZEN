<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use App\Filament\Resources\Pages\Concerns\HasResourceIndexNavigation;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClient extends EditRecord
{
    use HasResourceIndexNavigation;

    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [$this->getBackToIndexAction(), Actions\DeleteAction::make()];
    }
}
