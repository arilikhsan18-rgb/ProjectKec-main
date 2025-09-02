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
        // Tabel untuk pengguna (users)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama lengkap pengguna
            $table->string('username')->unique(); // Username unik untuk login sistematis
            $table->string('email')->unique()->nullable(); // Email, bisa null jika tidak wajib
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('status', ['active', 'pending', 'inactive'])->default('active');
            
            // --- PERBAIKAN 1: Kolom 'role_id' Dihapus ---
            // Kolom ini tidak lagi diperlukan karena Spatie akan mengelola relasi
            // antara user dan role menggunakan tabel terpisah (model_has_roles).
            // $table->unsignedBiginteger('role_id'); // <-- BARIS INI DIHAPUS

            $table->unsignedBiginteger('parent_id')->nullable(); // Ini tetap ada untuk hierarki Anda

            // Kolom untuk informasi wilayah (memudahkan query dan tampilan)
            $table->string('nama_kelurahan')->nullable();
            $table->string('nomor_rw', 3)->nullable();
            $table->string('nomor_rt', 3)->nullable();

            $table->rememberToken();
            $table->timestamps();

            // --- PERBAIKAN 2: Foreign Key untuk 'role_id' Dihapus ---
            // Karena kolom 'role_id' sudah dihapus, foreign key-nya juga harus dihapus.
            // $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade'); // <-- BARIS INI DIHAPUS
            
            // Foreign Key untuk parent_id tetap ada.
            $table->foreign('parent_id')->references('id')->on('users')->onDelete('set null');
        });

        // Tabel standar Laravel untuk reset password
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Tabel standar Laravel untuk session
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus tabel dalam urutan terbalik untuk menghindari error foreign key
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');

        // --- PERBAIKAN 3: Drop 'roles' Dihapus ---
        // File migrasi ini tidak lagi bertanggung jawab untuk membuat tabel 'roles',
        // jadi seharusnya tidak bertanggung jawab untuk menghapusnya.
        // Biarkan migrasi dari Spatie yang mengurusnya.
        // Schema::dropIfExists('roles'); // <-- BARIS INI DIHAPUS
    }
};

