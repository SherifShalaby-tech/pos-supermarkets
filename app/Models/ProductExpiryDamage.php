<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductExpiryDamage extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault(['name' => '']);
    }
    public function variation(){
        return $this->belongsTo(Variation::class);
    }
    public function addedBy(){
        return $this->belongsTo(User::class, 'added_by', 'id')->withDefault(['name' => '']);
    }

}
