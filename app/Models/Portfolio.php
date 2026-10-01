<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model {
    use HasFactory;
    protected $fillable = ['category_id','title','slug','type','thumbnail','video_url','description','client_name','event_date','is_featured','order'];
    protected $casts = ['is_featured' => 'boolean', 'event_date' => 'date'];
    
    public function category() { return $this->belongsTo(Category::class); }
    public function images() { return $this->hasMany(PortfolioImage::class)->orderBy('order'); }
    public function getRouteKeyName() { return 'slug'; }
    
    public function getThumbnailUrlAttribute() {
        if ($this->thumbnail) return asset('storage/' . $this->thumbnail);
        return asset('images/placeholder.jpg');
    }

    public function getGalleryAttribute()
    {
        return $this->images()->pluck('image_path')->all();
    }
    
    public function getYoutubeIdAttribute() {
        if (!$this->video_url) return null;
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\s]+)/', $this->video_url, $matches);
        return $matches[1] ?? null;
    }
    
    public function getYoutubeThumbnailAttribute() {
        if ($id = $this->youtube_id) {
            return "https://img.youtube.com/vi/{$id}/maxresdefault.jpg";
        }
        return $this->thumbnail_url;
    }
}
