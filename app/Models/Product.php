<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Product extends Model {
    use HasFactory;
    protected $attributes = ['bisa_dijual' => false, 'bisa_disewa' => true];
    protected $fillable = ['category_id','name','slug','description','specification','price','thumbnail','is_featured','status','order','meta_description','bisa_dijual','bisa_disewa'];
    protected $casts = ['is_featured' => 'boolean', 'status' => 'boolean', 'bisa_dijual' => 'boolean', 'bisa_disewa' => 'boolean'];

    protected static function booted(): void
    {
        static::saving(function (Product $product): void {
            if (! $product->bisa_dijual && ! $product->bisa_disewa) {
                throw ValidationException::withMessages([
                    'bisa_dijual' => 'Produk harus bisa dijual atau disewa.',
                ]);
            }
        });
    }
    
    public function category() { return $this->belongsTo(Category::class); }
    public function images() { return $this->hasMany(ProductImage::class)->orderBy('order'); }
    
    public function getRouteKeyName() { return 'slug'; }
    
    public function getThumbnailUrlAttribute() {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        return asset('images/product-placeholder.svg');
    }
    
    public function getWhatsappUrlAttribute() {
        return $this->whatsappRentalUrl();
    }

    public function getWhatsappRentalUrlAttribute() {
        return $this->whatsappRentalUrl();
    }

    public function getWhatsappSaleUrlAttribute() {
        $message = 'Halo, saya tertarik untuk membeli ' . $this->name . '. Mohon info harga dan ketersediaan.';

        return $this->whatsappUrlForMessage($message);
    }

    private function whatsappRentalUrl(): string
    {
        $message = 'Halo, saya tertarik untuk menyewa ' . $this->name . '. Mohon informasi lebih lanjut.';

        return $this->whatsappUrlForMessage($message);
    }

    private function whatsappUrlForMessage(string $message): string
    {
        $phone = config('company.whatsapp', '6281113051007');
        $text = urlencode($message);

        return "https://wa.me/{$phone}?text={$text}";
    }
}
