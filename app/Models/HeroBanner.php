<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroBanner extends Model
{
    protected $fillable = ['titulo', 'subtitulo', 'imagem_url', 'link_destino', 'ordem', 'ativo'];
}
