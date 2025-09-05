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
        Schema::create('geografis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // =======================================================
            // VVV INI ADALAH PERBAIKANNYA VVV
            $table->string('luas_wilayah')->nullable(); // Ganti 'luas' menjadi 'luas_wilayah'
            // =======================================================

            $table->string('batas_wilayah_utara')->nullable();
            $table->string('batas_wilayah_selatan')->nullable();
            $table->string('batas_wilayah_barat')->nullable();
            $table->string('batas_wilayah_timur')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geografis');
    }
};