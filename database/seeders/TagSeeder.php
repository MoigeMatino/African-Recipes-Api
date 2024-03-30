<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Recipe;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // for ($i = 0; $i < 100; $i++) {
        //     Tag::create([
        //         'tag' => fake()->word,
        //     ]);
        // }

        $recipes = Recipe::all();
        $comments = Comment::all();

        $taggable_recipe_count = floor($recipes->count() * 0.3);
        $taggable_recipe_keys = array_rand($recipes->all(), $taggable_recipe_count);
        foreach ($taggable_recipe_keys as $key) {
            // $random_tag = $tags->random();
            $recipe = $recipes[$key];
            $tag = $recipe->tags()->make(['tag' => fake()->word]);
            $tag->save();
        }

        $taggable_comments_count = floor($recipe->count() * 0.2);
        $taggable_comments_keys = array_rand($comments->all(), $taggable_comments_count);
        foreach ($taggable_comments_keys as $key) {
            $comment = $comments[$key];
            $tag = $comment->tags()->create(['tag' => fake()->word]);
        }
    }
}
