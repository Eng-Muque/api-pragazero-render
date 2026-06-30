<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Adiciona as colunas de categoria após o nome
            $table->string('categoria')->default('Limpeza em Superfície')->after('name');
            $table->string('subcategoria')->nullable()->after('categoria');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['categoria', 'subcategoria']);
        });
    }
};
