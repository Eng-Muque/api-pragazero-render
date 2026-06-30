<?php

namespace App\Modules\Product\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'name' => 'sometimes|required|string|max:255',

            'description' => 'sometimes|required|string',

            'categoria' => 'nullable|string|max:100',

            'subcategoria' => 'nullable|string|max:100',

            'price' => 'sometimes|required|numeric|min:0',

            'stock' => 'sometimes|required|integer|min:0',

            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'

        ];
    }
}