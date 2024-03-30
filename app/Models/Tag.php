<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['tag'];

    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'recipe_tag', 'tag_id', 'recipe_id')->withTimestamps();
    }

    public function newsletters(): MorphMany
    {
        return $this->morphMany(Newsletter::class, 'taggable');
    }

    public function taggable()
    {
        return $this->morphTo();
    }
}
