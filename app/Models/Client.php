<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model {
    use HasFactory;
    protected $fillable = ['name','logo_path','website','order','is_active'];
    protected $casts = ['is_active' => 'boolean'];
    public function getLogoUrlAttribute() {
        if ($this->logo_path) return asset('storage/' . $this->logo_path);
        return null;
    }
}
