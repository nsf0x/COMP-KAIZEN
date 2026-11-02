<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use App\Filament\Resources\Pages\Concerns\HasResourceIndexNavigation;
use Filament\Resources\Pages\CreateRecord;

class CreateClient extends CreateRecord
{
    use HasResourceIndexNavigation;

    protected static string $resource = ClientResource::class;
}
