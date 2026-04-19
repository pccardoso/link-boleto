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
        Schema::table('bill_update', function (Blueprint $table) {
            $table->string('codigo_situacao_boleto')->default('2');
            $table->string('descricao_situacao_boleto')->default('ABERTO');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bill_update', function (Blueprint $table) {
            $table->dropColumn('codigo_situacao_boleto');
            $table->dropColumn('descricao_situacao_boleto');
        });
    }
};
