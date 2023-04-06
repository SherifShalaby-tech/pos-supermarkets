<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    use HasFactory;

    protected $fillable = [
      "name",
      "translations",
    ];

    protected $casts = [
        'translations' => 'array',
    ];

    public function getNameAttribute($name)
    {
        $translations = !empty($this->translations['name']) ? $this->translations['name'] : [];
        if (!empty($translations)) {
            $lang = session('language');
            if (!empty($translations[$lang])) {
                return $translations[$lang];
            }
        }
        return $name;
    }
    public static function getDropdown()
    {
        return Manufacturer::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
    }
}
