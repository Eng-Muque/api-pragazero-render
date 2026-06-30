<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'servico' => $this->name,
            'detalhes' => $this->description,
            'foto' => $this->image ? url('storage/' . $this->image) : null,
            'solicitar_orcamento_url' => url("/api/services/{$this->id}/quote") 
        ];
    }

}
