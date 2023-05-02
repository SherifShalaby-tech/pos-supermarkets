<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInAdjustment extends Model
{
    use HasFactory;
    protected $fillable = [
        "total_shortage_value",
        "created_by",
        "store_id"
    ];
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault(['name' => '']);
    }
}
