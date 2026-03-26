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
        Schema::create('bill_update', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo_boleto')->nullable();
            $table->integer('nosso_numero')->nullable();
            $table->string('cpf_cnpj')->nullable();
            $table->string('associado')->nullable();
            $table->text('linha_digitavel')->nullable();
            $table->text('link_boleto')->nullable();
            $table->string('nova_data_vencimento')->nullable();

            $table->unsignedBigInteger('hash_plate_id')->nullable();
            $table->foreign('hash_plate_id')->references('id')->on('hash_plate')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_update');
    }
};
