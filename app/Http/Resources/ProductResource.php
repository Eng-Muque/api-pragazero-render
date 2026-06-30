<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->name,
            'categoria'    => $this->categoria,
            'subcategoria' => $this->subcategoria,
            'descricao' => $this->description,
            'preco' => number_format($this->price, 2, ',', '.'),
            'link_imagem' => $this->image ? url('storage/' . $this->image) : null,
            'disponivel' => $this->stock > 0
        ];

    }

}
