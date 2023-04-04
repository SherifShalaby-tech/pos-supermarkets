<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class manufacturingProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        "status",
        "manufacturing_id",
        "product_id",
        "quantity",
    ];
    protected $casts = [
        "quantity" => "double"
    ];
    public function product(){
        return $this->belongsTo(Product::class,"product_id","id");
    }
    public function manufacturing(){
        return $this->belongsTo(Manufacturing::class,"manufacturing_id","id");
    }
}
