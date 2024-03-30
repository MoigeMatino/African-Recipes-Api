<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recipes = Recipe::all();
        foreach ($recipes as $recipe) {
            for ($i = 0; $i < 5; $i++) {
                $user = User::all()->random();
                $comment = $recipe->comments()->make(['comment' => fake()->sentence(5)]);
                $comment->author()->associate($user);
                $comment->save();
                // $comment->comments()->make(['comment' => fake()->sentence()])->associate($user)->save();
                $new_comment = $comment->comments()->make(['comment' => fake()->sentence(5)]);
                $new_comment->author()->associate($user);
                $new_comment->save();
            }
        }
    }
}
