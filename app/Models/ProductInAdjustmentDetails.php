<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInAdjustmentDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        "product_id",
        "variation_id",
        "product_adjustments_id",
        "old_stock",
        "new_stock",
        "shortage",
        "shortage_value",
       " old_purchase_price",
       "new_purchase_price",
       "old_sell_price",
       "new_sell_price",
    ];
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function productAjustment(){
        return $this->belongsTo(ProductInAdjustment::class,'product_adjustments_id');
    }
}
