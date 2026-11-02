<?php

namespace App\Filament\Resources\PortfolioResource\Pages;

use App\Filament\Resources\Pages\Concerns\HasResourceIndexNavigation;
use App\Filament\Resources\PortfolioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPortfolio extends EditRecord
{
    use HasResourceIndexNavigation;

    protected static string $resource = PortfolioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getBackToIndexAction(),
            Actions\DeleteAction::make(),
        ];
    }
}
