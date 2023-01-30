<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrinterProduct extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'printer_product';
}
