<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipesTable extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('recipes')) {
            Schema::create('recipes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('cuisine_id')->nullable()->constrained()->nullOnDelete();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->text('instructions')->nullable();
                $table->integer('prep_time')->nullable();
                $table->integer('cook_time')->nullable();
                $table->integer('servings')->nullable();
                $table->string('difficulty')->nullable();
                $table->string('image')->nullable();
                $table->string('status')->default('published');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
}
