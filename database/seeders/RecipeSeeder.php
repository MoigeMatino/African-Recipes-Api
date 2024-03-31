<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (User::all() as $user) {
            // for ($i = 0; $i < 20; $i++) { // Every user will have 20 recipes
            $user->recipes()->create([
                'title' => fake()->sentence,
                'description' => fake()->paragraph,
                'instructions' => fake()->randomHtml,
                'prep_time' => fake()->text,
                'cook_time' => fake()->sentence(2),
                'total_time' => fake()->sentence(2),
                'servings' => fake()->randomDigit,
                'image_url' => fake()->imageUrl(640, 480, 'animals', true),
                'premium' => fake()->randomElement([true, false]),
                'ingredients' => json_encode(array_fill(0, 3, [
                    'name' => fake()->sentence,
                    'amount' => fake()->randomNumber(2, false),
                    'unit' => fake()->randomElement(['g', 'kg', 'ml', 'l', 'cup', 'piece']),
                ])),
                'nutritional_info' => json_encode([
                    'calories' => fake()->randomFloat(2, 0, 1000),
                    'saturated_fat' => fake()->randomFloat(2, 0, 1000),
                    'carbohydrates' => fake()->randomFloat(2, 1, 1000),
                    'protein' => fake()->randomFloat(2, 1, 1000),
                ]),
            ]);
            // }
        }

        // Add Collaborators to recipes
        $recipes = Recipe::all(); // Get all users. This will prevent Eloquent from having to re-run queries subsequently

        foreach ($recipes->random(floor($recipes->count() * 0.5)) as $recipe) { // Half the recipies in our collection will have collaborators
            $users = User::whereNot('id', $recipe->author->id)->inRandomOrder()->take(fake()->numberBetween(1, 3))->get(); // Get 1-3 users that are not the recipe's author

            foreach ($users as $user) {
                $recipe->collaborators()->attach($user); // Populate the pivot table
            }
        }

        // Add likes to Recipes
        $recipes = Recipe::all();

        foreach ($recipes->random(floor($recipes->count() * 0.9)) as $recipe) { // At least 90% of our recipies will have > 1 like
            $users = User::whereNot('id', $recipe->author->id)->inRandomOrder()->get();
            foreach ($users->take(fake()->numberBetween(1, floor($user->count() * 0.6))) as $user) { // At least 60% of our users will have liked a recipe
                $user->liked_recipes()->attach($recipe);
            }
        }

        /*
         * Todo: Add ratings to pivot table such that only users who've liked a recipe should rate it 3 stars and above
         * and other recipies can get rated between 1 and 2. Note that not all recipes should have ratings
         */

        foreach (Recipe::has('users_liked')->with('users_liked')->get() as $recipe) { // Only users who've liked should be able to add a rating >= 3
            $users_liked = $recipe->users_liked;

            $sample_count = $users_liked->count() * 0.9;

            $final_collection = $users_liked->random(ceil($sample_count));

            foreach ($final_collection as $user) {
                $user->rated_recipes()->attach($recipe, ['rating' => fake()->numberBetween(3, 5)]);
            }
        }

        foreach (Recipe::doesntHave('users_liked')->get() as $recipe) { // Dislikers of Recipes musn't have liked the recipe before
            foreach (User::inRandomOrder()->take(10)->get() as $user) {
                $user->rated_recipes()->attach($recipe, ['rating' => fake()->numberBetween(1, 2)]);
            }
        }
    }
}
