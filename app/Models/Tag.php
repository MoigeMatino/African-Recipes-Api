<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['tag'];

    public function recipes(): MorphMany
    {
        return $this->morphMany(Recipe::class, 'taggable');
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
