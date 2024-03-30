<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // https://fakerphp.github.io/
        $this->call([
            UserSeeder::class,
            SubscriberSeeder::class,
            NewsletterSeeder::class,
            RecipeSeeder::class,
            // !Need to discuss relevance of this class
            // RecipeTagAssignmentSeeder::class,
            CommentSeeder::class,
            TagSeeder::class,
        ]);
    }
}
