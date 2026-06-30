<?php

namespace App\Modules\Product\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'name' => 'required|string|max:255',

            'description' => 'required|string',

            'categoria' => 'nullable|string|max:100',

            'subcategoria' => 'nullable|string|max:100',

            'price' => 'required|numeric|min:0',

            'stock' => 'required|integer|min:0',

            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'

        ];
    }
}