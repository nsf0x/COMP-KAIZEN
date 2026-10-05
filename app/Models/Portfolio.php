<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model {
    use HasFactory;
    protected $fillable = ['title','slug','type','thumbnail','video_url','video_path','description','client_name','event_date','is_featured','order'];
    protected $casts = ['is_featured' => 'boolean', 'event_date' => 'date'];
    
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

    public function getVideoFileUrlAttribute(): ?string
    {
        return $this->video_path ? asset('storage/' . $this->video_path) : null;
    }
    
    public function getYoutubeIdAttribute() {
        if (!$this->video_url) return null;
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\s]+)/', $this->video_url, $matches);
        return $matches[1] ?? null;
    }

    public function getVideoEmbedUrlAttribute(): ?string
    {
        if (!$this->video_url) {
            return null;
        }

        $host = strtolower(parse_url($this->video_url, PHP_URL_HOST) ?? '');
        $path = trim(parse_url($this->video_url, PHP_URL_PATH) ?? '', '/');

        if (in_array($host, ['youtu.be', 'www.youtu.be'], true)) {
            $videoId = explode('/', $path)[0] ?? '';
        } elseif (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com'], true)) {
            parse_str(parse_url($this->video_url, PHP_URL_QUERY) ?? '', $query);
            $videoId = $query['v'] ?? (preg_match('~^(?:embed|shorts)/([^/]+)~', $path, $matches) ? $matches[1] : '');
        } elseif (in_array($host, ['vimeo.com', 'www.vimeo.com', 'player.vimeo.com'], true)) {
            preg_match('~(?:video/)?(\d+)~', $path, $matches);
            $videoId = $matches[1] ?? '';

            return $videoId !== '' ? "https://player.vimeo.com/video/{$videoId}" : null;
        } else {
            return null;
        }

        return preg_match('/^[a-zA-Z0-9_-]+$/', $videoId)
            ? 'https://www.youtube-nocookie.com/embed/' . $videoId
            : null;
    }

    public function getVideoIsFileAttribute(): bool
    {
        $path = parse_url($this->video_url ?? '', PHP_URL_PATH) ?? '';

        return in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['mp4', 'webm', 'ogg'], true);
    }
    
    public function getYoutubeThumbnailAttribute() {
        if ($id = $this->youtube_id) {
            return "https://img.youtube.com/vi/{$id}/maxresdefault.jpg";
        }
        return $this->thumbnail_url;
    }
}
