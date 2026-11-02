<?php

namespace App\Filament\Resources\PortfolioResource\Pages;

use App\Filament\Resources\PortfolioResource;
use App\Filament\Resources\Pages\Concerns\HasResourceIndexNavigation;
use Filament\Resources\Pages\CreateRecord;

class CreatePortfolio extends CreateRecord
{
    use HasResourceIndexNavigation;

    protected static string $resource = PortfolioResource::class;

    protected function getHeaderActions(): array
    {
        return [$this->getBackToIndexAction()];
    }
}
