<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPostImage extends Model
{
    protected $fillable = ['blog_post_id', 'image_path', 'caption', 'order'];

    public function blogPost()
    {
        return $this->belongsTo(BlogPost::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }
}
