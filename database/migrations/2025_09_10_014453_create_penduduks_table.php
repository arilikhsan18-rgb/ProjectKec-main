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
        Schema::create('penduduks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('year');
            $table->enum('gender', ['laki-laki','perempuan']);
            $table->enum('resident', ['tetap', 'pindahan']);
            $table->string('religion');
            $table->enum('education',['belum sekolah','masih sekolah','putus sekolah']);
            $table->enum('occupation', ['bekerja','tidak bekerja']);
            $table->enum('lampid', ['kelahiran','kematian','pindah','datang'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penduduks');
    }
};
