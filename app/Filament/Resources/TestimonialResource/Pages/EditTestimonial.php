<?php

namespace App\Filament\Resources\TestimonialResource\Pages;

use App\Filament\Resources\Pages\Concerns\HasResourceIndexNavigation;
use App\Filament\Resources\TestimonialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestimonial extends EditRecord
{
    use HasResourceIndexNavigation;

    protected static string $resource = TestimonialResource::class;

    protected function getHeaderActions(): array
    {
        return [$this->getBackToIndexAction(), Actions\DeleteAction::make()];
    }
}
