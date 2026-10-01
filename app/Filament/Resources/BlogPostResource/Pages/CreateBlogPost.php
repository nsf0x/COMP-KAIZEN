<?php

namespace App\Filament\Resources\BlogPostResource\Pages;

use App\Filament\Resources\BlogPostResource;
use App\Filament\Resources\Pages\Concerns\HasResourceIndexNavigation;
use Filament\Resources\Pages\CreateRecord;

class CreateBlogPost extends CreateRecord
{
    use HasResourceIndexNavigation;

    protected static string $resource = BlogPostResource::class;

    protected function getHeaderActions(): array
    {
        return [$this->getBackToIndexAction()];
    }
}
