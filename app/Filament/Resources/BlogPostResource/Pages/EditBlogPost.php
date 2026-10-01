<?php

namespace App\Filament\Resources\BlogPostResource\Pages;

use App\Filament\Resources\BlogPostResource;
use App\Filament\Resources\Pages\Concerns\HasResourceIndexNavigation;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBlogPost extends EditRecord
{
    use HasResourceIndexNavigation;

    protected static string $resource = BlogPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getBackToIndexAction(),
            Actions\DeleteAction::make(),
        ];
    }
}
