<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eskul_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon', 10)->default('🎯');
            $table->text('description');
            $table->text('detail');
            $table->string('schedule');
            $table->string('contact')->nullable();
            $table->text('activities')->nullable();
            $table->string('achievements')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eskul_profiles');
    }
};
