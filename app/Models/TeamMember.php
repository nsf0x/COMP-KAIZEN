<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model {
    use HasFactory;
    protected $fillable = ['name','position','photo','description','order','is_active'];
    protected $casts = ['is_active' => 'boolean'];
    public function getPhotoUrlAttribute() {
        if ($this->photo) return asset('storage/' . $this->photo);
        return asset('images/avatar-placeholder.jpg');
    }
}
