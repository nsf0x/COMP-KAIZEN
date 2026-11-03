<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaPage extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'unique_content',
        'delivery_time',
        'venue_details',
        'portfolio_details',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function isReadyForPublication(): bool
    {
        return $this->is_published
            && filled($this->unique_content)
            && (
                filled($this->delivery_time)
                || filled($this->venue_details)
                || filled($this->portfolio_details)
            );
    }
}
