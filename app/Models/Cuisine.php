<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Cuisine extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'country'];

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
}
