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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')
                  ->constrained('tickets')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();
            $table->string('invoice_number')->unique();
            $table->string('buyer_name');
            $table->string('buyer_email');
            $table->string('buyer_whatsapp', 20);
            $table->string('buyer_class', 20);
            $table->unsignedSmallInteger('quantity');
            $table->unsignedBigInteger('base_price');
            $table->unsignedSmallInteger('unique_code'); // 100–999 for Moota verification
            $table->unsignedBigInteger('total_price');
            $table->enum('status', ['pending', 'success', 'expired'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
