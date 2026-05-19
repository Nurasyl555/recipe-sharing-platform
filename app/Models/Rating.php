<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'recipe_id', 'score', 'comment'];

    /**
     * Accessor for score to support legacy 'rating' column.
     */
    public function getScoreAttribute($value)
    {
        if (array_key_exists('score', $this->attributes)) {
            return $this->attributes['score'];
        }
        return $this->attributes['rating'] ?? $value;
    }

    public function setScoreAttribute($value)
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn($this->getTable(), 'score')) {
                $this->attributes['score'] = $value;
            } else {
                $this->attributes['rating'] = $value;
            }
        } catch (\Throwable $e) {
            $this->attributes['score'] = $value;
        }
    }

    /**
     * Accessor/mutator for comment to be resilient if migration not applied yet.
     */
    public function getCommentAttribute($value)
    {
        return $this->attributes['comment'] ?? null;
    }

    public function setCommentAttribute($value)
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn($this->getTable(), 'comment')) {
                $this->attributes['comment'] = $value;
            } else {
                // store in attributes to avoid exceptions until migrations are run
                $this->attributes['comment'] = $value;
            }
        } catch (\Throwable $e) {
            $this->attributes['comment'] = $value;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
