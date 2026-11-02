<?php

namespace App\Filament\Resources\TestimonialResource\Pages;

use App\Filament\Resources\Pages\Concerns\HasResourceIndexNavigation;
use App\Filament\Resources\TestimonialResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTestimonial extends CreateRecord
{
    use HasResourceIndexNavigation;

    protected static string $resource = TestimonialResource::class;
}
