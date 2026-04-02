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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description');
            $table->string('instructions');
            $table->integer('prep_time')->comment('minutes');
            $table->integer('cook_time')->comment('minutes');
            $table->integer('servings')->default(2);
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy');
            $table->string('image')->nullable();
            $table->enum('status', ['draft', 'published', 'rejected'])->default('draft');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cuisine_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
