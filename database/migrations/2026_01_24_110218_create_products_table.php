<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');          // Nome do produto
            $table->text('description');     // Descrição detalhada
            $table->decimal('price', 10, 2); // Preço (ex: 99.90)
            $table->integer('stock');        // Quantidade em estoque
            $table->string('image')->nullable(); // Caminho da foto
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
