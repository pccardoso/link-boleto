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
        Schema::create('hash_plate', function (Blueprint $table) {
            $table->id();
            $table->string('plate')->nullable()->comment('Placa do Usuário');
            $table->string('hash')->comment('Hash da vistoria');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hash_plate');
    }
};
