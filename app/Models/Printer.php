<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'printers';
    protected $casts = ['is_active' => 'boolean','is_cashier' => 'boolean'];

    public function scopeActive(){
        return $this->is_active == 1 ? trans('lang.active') : trans('lang.not_active');
    }

    public function products(){
        return $this->hasMany('App\Models\PrinterProduct','printer_id');
    }
}
