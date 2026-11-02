<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model {
    use HasFactory;
    protected $fillable = ['client_name','company','message','rating','photo','is_active','order'];
    protected $casts = ['is_active' => 'boolean', 'rating' => 'integer'];
    public function getPhotoUrlAttribute() {
        if ($this->photo) return asset('storage/' . $this->photo);
        return asset('images/avatar-placeholder.jpg');
    }
}
