<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'instructions',
        'prep_time', 'cook_time', 'servings',
        'difficulty', 'image', 'status',
        'user_id', 'category_id', 'cuisine_id',
    ];

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

    // Many-to-many
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'ingredient_recipe')
            ->withPivot('amount', 'unit')
            ->withTimestamps();
    }

//    public function ratings()
//    {
//        return $this->hasMany(Rating::class);
//    }
//
//    public function favoritedBy()
//    {
//        return $this->belongsToMany(User::class, 'favorites');
//    }

    public function isPublished() : bool
    {
        return $this->status === 'published';
    }

    public function isOwnedBy(User $user) : bool
    {
        return $this->user_id === $user->id;
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/default-recipe.jpg');
    }

}
