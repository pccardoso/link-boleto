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
        Schema::create('upload_hash', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->unsignedBigInteger('hash_plate_id');
            $table->foreign('hash_plate_id')->references('id')->on('hash_plate')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_hash');
    }
};
