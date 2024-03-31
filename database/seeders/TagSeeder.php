<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Recipe;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recipes = Recipe::all();
        $comments = Comment::all();

        $taggable_recipe_count = floor($recipes->count() * 0.99); // 99% of recipes have tags
        $taggable_recipe_keys = array_rand($recipes->all(), $taggable_recipe_count);
        foreach ($taggable_recipe_keys as $key) {
            $recipe = $recipes[$key];
            $recipe->tags()->create(['tag' => fake()->word]);
        }

        $taggable_comments_count = floor($recipe->count() * 0.2);
        $taggable_comments_keys = array_rand($comments->all(), $taggable_comments_count);
        foreach ($taggable_comments_keys as $key) {
            $comment = $comments[$key];
            $comment->tags()->create(['tag' => fake()->word]);
        }
    }
}
