<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->uuid('download_token')->nullable()->unique()->after('invoice_number');
        });

        // Backfill existing rows with UUIDs
        DB::table('transactions')->whereNull('download_token')->get()->each(function ($row) {
            DB::table('transactions')
                ->where('id', $row->id)
                ->update(['download_token' => Str::uuid()->toString()]);
        });

        // Now make the column non-nullable
        Schema::table('transactions', function (Blueprint $table) {
            $table->uuid('download_token')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('download_token');
        });
    }
};
