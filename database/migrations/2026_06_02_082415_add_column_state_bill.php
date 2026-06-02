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
            $table->string('state')->nullable()->comment('Estado do boleto, necessário para buscar o status do boleto na SGA');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bill_update', function (Blueprint $table) {
            $table->dropColumn('state');
        });
    }
};
