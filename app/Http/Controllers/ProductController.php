<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('stock', '>', 0)->get();
        // Retorna a coleção formatada
        return ProductResource::collection($products);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        // Retorna um único item formatado
        return new ProductResource($product);
    }
}
