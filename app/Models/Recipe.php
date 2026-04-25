<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;   // BUGFIX: was missing, caused Str::slug() to fail

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'instructions',
        'prep_time', 'cook_time', 'servings',
        'difficulty', 'image', 'status',
        'user_id', 'category_id', 'cuisine_id',
    ];

    // Auto-generate slug on create
    protected static function booted(): void
    {
        static::creating(function (Recipe $recipe) {
            $recipe->slug = Str::slug($recipe->title) . '-' . uniqid();
        });
    }

    // Relationsjip

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cuisine()
    {
        return $this->belongsTo(Cuisine::class);
    }

    /**
     * Ingredients via pivot.
     * BUGFIX: pivot table name was 'ingredient_recipe' — corrected to 'recipe_ingredient'
     * to match the actual migration file.
     */
    // Many-to-many
    public function ingredients()
    {
        // ИСПРАВЛЕНИЕ: здесь должно быть 'recipe_ingredient', как в твоей миграции
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredient')
            ->withPivot('amount', 'unit')
            ->withTimestamps();
    }

    /**
     * Ratings for this recipe (Person 3 will implement RatingController).
     * Uncommented so RecipeController->show() can eager-load ratings.
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Users who saved this recipe as favourite (Person 3).
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    // -------------------------
    // Helpers
    // -------------------------

    public function isPublished() : bool
    {
        return $this->status === 'published';
    }

    public function isOwnedBy(User $user) : bool
    {
        return $this->user_id === $user->id;
    }

    /**
     * Average rating score (used by Person 3's trending logic).
     */
    public function getAverageRatingAttribute(): float
    {
        return round($this->ratings()->avg('score') ?? 0, 1);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/default-recipe.jpg');
    }

}
