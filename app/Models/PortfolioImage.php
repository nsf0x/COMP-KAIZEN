<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioImage extends Model
{
    protected $fillable = ['portfolio_id', 'image_path', 'caption', 'order'];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }
}
