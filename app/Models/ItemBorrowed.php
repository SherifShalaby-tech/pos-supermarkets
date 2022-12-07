<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemBorrowed extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function admin(){
        return $this->belongsTo('App\Models\User','admin_id');
    }

    public function customer(){
        return $this->belongsTo('App\Models\Customer','customer_id');
    }
}
