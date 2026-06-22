<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Recipe extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'title', 'slug',
        'description', 'instructions', 'cook_time', 'servings', 'image'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($recipe) {
            $recipe->slug = Str::slug($recipe->title) . '-' . Str::random(5);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function averageRating()
    {
        return $this->ratings()->avg('score') ?? 0;
    }
}