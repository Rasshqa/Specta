<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Truncate the tickets table to clear old VIP/Regular types safely with FKs disabled
        Schema::disableForeignKeyConstraints();
        DB::table('tickets')->truncate();
        Schema::enableForeignKeyConstraints();

        // Insert a SINGLE new row for "Tiket Biasa" with combined quota and new price (110000)
        DB::table('tickets')->insert([
            'ticket_name'     => 'Tiket Biasa',
            'price'           => 110000,
            'quota'           => 600, // combined quota e.g., 500 + 100
            'remaining_quota' => 600,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert by truncating and re-inserting old data if necessary, or just truncate
        Schema::disableForeignKeyConstraints();
        DB::table('tickets')->truncate();
        Schema::enableForeignKeyConstraints();
        
        DB::table('tickets')->insert([
            [
                'ticket_name'     => 'Presale 1',
                'price'           => 110000,
                'quota'           => 500,
                'remaining_quota' => 500,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'ticket_name'     => 'VIP',
                'price'           => 110000,
                'quota'           => 100,
                'remaining_quota' => 100,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]
        ]);
    }
};
