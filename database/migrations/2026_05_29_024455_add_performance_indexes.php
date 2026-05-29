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
        Schema::table('transactions', function (Blueprint $table) {
            $table->index(['buyer_email', 'created_at']);
            $table->index(['buyer_whatsapp', 'created_at']);
        });

        Schema::table('eskul_profiles', function (Blueprint $table) {
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['buyer_email', 'created_at']);
            $table->dropIndex(['buyer_whatsapp', 'created_at']);
        });

        Schema::table('eskul_profiles', function (Blueprint $table) {
            $table->dropIndex(['sort_order']);
        });
    }
};
