<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
    Schema::create('hero_banners', function (Blueprint $table) {
        $table->id();
        $table->string('titulo');
        $table->string('subtitulo')->nullable();
        $table->string('imagem_url');
        $table->string('link_destino')->default('/contato');
        $table->integer('ordem')->default(0);
        $table->boolean('ativo')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_banners');
    }
};
