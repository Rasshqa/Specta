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
        Schema::create('ticket_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')
                  ->constrained('transactions')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->string('unique_ticket_code')->unique();
            $table->boolean('is_scanned')->default(false);
            $table->timestamp('scanned_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_codes');
    }
};
