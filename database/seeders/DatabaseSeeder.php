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
        $start_memory_usage = memory_get_usage();
        $start_time = microtime(true);
        // https://fakerphp.github.io/
        $this->call([
            UserSeeder::class,
            SubscriberSeeder::class,
            NewsletterSeeder::class,
            RecipeSeeder::class,
            CommentSeeder::class,
            TagSeeder::class,
        ]);
        $end_memory_usage = memory_get_peak_usage();
        $end_time = microtime(true);
        $total_time = $end_time - $start_time;
        dump('Total Execution time of seeders = '.($total_time / 60).'min');
        dump('Start Memory Usage = '.($start_memory_usage / 1000000).' MB');
        dump('End Memory Usage = '.($end_memory_usage / 1000000).' MB');
    }
}
