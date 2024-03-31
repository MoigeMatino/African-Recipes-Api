<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }

    public function liked_recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'likes', 'user_id', 'recipe_id')->withTimestamps();
    }

    public function featured_recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'collaborators', 'user_id', 'recipe_id')->withTimestamps();
    }

    public function rated_recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'ratings', 'user_id', 'recipe_id')->withTimestamps()->withPivot('rating');
    }
}
