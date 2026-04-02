<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'ingredient_recipe')
            ->withPivot('amount', 'unit')
            ->withTimestamps();
    }
}
