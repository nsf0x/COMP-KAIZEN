<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    use HasFactory;
    protected $fillable = ['name','slug','type','icon','description','order','is_active'];
    protected $casts = ['is_active' => 'boolean'];
    
    public function products() { return $this->hasMany(Product::class); }
    public function portfolios() { return $this->hasMany(Portfolio::class); }

    public function scopeAvailableForCatalog(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->whereHas('products', fn (Builder $products) => $products->where('status', true));
    }
    
    public function getRouteKeyName() { return 'slug'; }
}
