<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model {
    use HasFactory;
    protected $fillable = ['client_name','company','message','rating','photo','event_type','rented_product','location','event_date','is_active','is_published','order'];
    protected $casts = ['is_active' => 'boolean', 'is_published' => 'boolean', 'rating' => 'integer', 'event_date' => 'date'];
    public function getPhotoUrlAttribute() {
        if ($this->photo) return asset('storage/' . $this->photo);
        return asset('images/avatar-placeholder.jpg');
    }
}
