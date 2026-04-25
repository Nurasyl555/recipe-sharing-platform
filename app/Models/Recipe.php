<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'instructions',
        'prep_time',
        'cook_time',
        'servings',
        'difficulty',
        'image',
        'status',
        'user_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (Recipe $recipe) {
            if (empty($recipe->slug) && ! empty($recipe->title)) {
                $recipe->slug = Str::slug($recipe->title).'-'.uniqid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
