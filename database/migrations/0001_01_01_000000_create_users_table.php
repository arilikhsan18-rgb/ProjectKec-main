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
        // Tabel untuk menyimpan peran (roles)
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // SUPERADMIN, KECAMATAN, etc.
            $table->timestamps();
        });

        // Tabel untuk pengguna (users)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama lengkap pengguna
            $table->string('username')->unique(); // Username unik untuk login sistematis
            $table->string('email')->unique()->nullable(); // Email, bisa null jika tidak wajib
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('status', ['active', 'pending', 'inactive'])->default('active');
            
            // Kolom untuk Foreign Key
            $table->unsignedBiginteger('role_id');
            $table->unsignedBiginteger('parent_id')->nullable();

            // Kolom untuk informasi wilayah (memudahkan query dan tampilan)
            $table->string('nama_kelurahan')->nullable();
            $table->string('nomor_rw', 3)->nullable();
            $table->string('nomor_rt', 3)->nullable();

            $table->rememberToken();
            $table->timestamps();

            // Mendefinisikan Foreign Key Constraints
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
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
        Schema::dropIfExists('roles');
    }
};
