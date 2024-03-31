<?php

namespace Database\Seeders;

use App\Models\Subscriber;
use Illuminate\Database\Seeder;

class SubscriberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 1000; $i++) {
            $email = fake()->safeemail.$i;
            Subscriber::updateOrCreate(
                [
                    'email' => $email,
                ],
                [
                    'name' => fake()->name,
                    'email' => $email,
                ]
            );
        }
    }
}
