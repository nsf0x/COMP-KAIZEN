<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model {
    use HasFactory;
    protected $fillable = ['title','slug','thumbnail','content','meta_description','author','published_at','is_published'];
    protected $casts = ['is_published' => 'boolean', 'published_at' => 'datetime'];
    
    public function images() { return $this->hasMany(BlogPostImage::class)->orderBy('order'); }

    public function getRouteKeyName() { return 'slug'; }
    public function getThumbnailUrlAttribute() {
        if ($this->thumbnail) return asset('storage/' . $this->thumbnail);
        return asset('images/placeholder.jpg');
    }
    public function getExcerptAttribute() {
        return str()->limit(strip_tags($this->content), 150);
    }
    public function getGalleryAttribute()
    {
        return $this->images()->pluck('image_path')->all();
    }
    
    public function scopePublished($query) {
        return $query->where('is_published', true);
    }
}
