<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'categoria',
        'subcategoria',
        'price',
        'stock',
        'image',
        'active',
    ];
}