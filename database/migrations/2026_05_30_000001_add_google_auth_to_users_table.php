<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom google_id dan jadikan kolom password nullable
     * untuk mendukung pengguna yang login via Google OAuth.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Jadikan password nullable — pengguna Google tidak memiliki password lokal
            $table->string('password')->nullable()->change();

            // Simpan Google ID untuk identifikasi unik per akun Google
            $table->string('google_id')->nullable()->unique()->after('remember_token');
        });
    }

    /**
     * Balikkan perubahan migrasi.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('google_id');
            $table->string('password')->nullable(false)->change();
        });
    }
};
