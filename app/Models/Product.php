<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   protected $fillable = ['name', 'description','categoria','subcategoria', 'price', 'stock', 'image'];
   
}
