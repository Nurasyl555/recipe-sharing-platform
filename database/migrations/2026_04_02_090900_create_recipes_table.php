<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// BUGFIX: original migration used 'name' but model/requests use 'title'
// Column renamed to 'title' to match the model's $fillable and FormRequests.

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('title');                         // ← was 'name', fixed to 'title'
            $table->string('slug')->unique();
            $table->text('description');
            $table->longText('instructions');
            $table->unsignedInteger('prep_time')->comment('minutes');
            $table->unsignedInteger('cook_time')->comment('minutes');
            $table->unsignedInteger('servings')->default(2);
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy');
            $table->string('image')->nullable();
            $table->enum('status', ['draft', 'published', 'rejected'])->default('draft');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cuisine_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
