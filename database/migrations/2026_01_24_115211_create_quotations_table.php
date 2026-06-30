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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();    // Quem pede
            $table->foreignId('service_id')->constrained(); // Qual serviço
            $table->text('client_notes');                   // Descrição do problema/necessidade
            $table->decimal('offered_price', 10, 2)->nullable(); // Preço que o Admin vai definir depois
            $table->string('status')->default('pendente');  // pendente, enviado, aprovado, concluido
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
