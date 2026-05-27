<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('winners', function (Blueprint $table) {
            $table->id();
            $table->string('rank');                     // e.g. "🏆 Juara 1"
            $table->string('name');
            $table->string('school');
            $table->string('category');
            $table->string('score')->nullable();        // Optional — reduces bloat
            $table->string('image_path')->nullable();   // Optional photo
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('winners');
    }
};
