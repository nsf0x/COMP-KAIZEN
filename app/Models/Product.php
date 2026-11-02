<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    use HasFactory;
    protected $fillable = ['category_id','name','slug','description','specification','price','thumbnail','is_featured','status','order','meta_description'];
    protected $casts = ['is_featured' => 'boolean', 'status' => 'boolean'];
    
    public function category() { return $this->belongsTo(Category::class); }
    public function images() { return $this->hasMany(ProductImage::class)->orderBy('order'); }
    
    public function getRouteKeyName() { return 'slug'; }
    
    public function getThumbnailUrlAttribute() {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        return asset('images/placeholder.jpg');
    }
    
    public function getWhatsappUrlAttribute() {
        $phone = config('company.whatsapp', '6281119998630');
        $text = urlencode('Halo, saya tertarik untuk menyewa ' . $this->name . '. Mohon informasi lebih lanjut.');
        return "https://wa.me/{$phone}?text={$text}";
    }
}
