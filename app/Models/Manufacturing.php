<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturing extends Model
{
    use HasFactory;
    protected $fillable = [
      "store_id",
      "manufacturer_id",
      "created_by",
      "edited_by",
    ];
    public function store(){
        return $this->belongsTo(Store::class,"store_id","id");
    }
    public function manufacturer(){
        return $this->belongsTo(Manufacturer::class,"manufacturer_id","id");
    }
    public function materials(){
        return $this->hasMany(manufacturingProduct::class,"manufacturing_id","id")->where("status","0");
    }
    public function material_recived(){
        return $this->hasMany(manufacturingProduct::class,"manufacturing_id","id")->where("status","1");
    }
    public function createdUser(){
        return $this->belongsTo(User::class,"created_by","id");
    }   public function editedUser(){
        return $this->belongsTo(User::class,"edited_by","id");
    }

}
