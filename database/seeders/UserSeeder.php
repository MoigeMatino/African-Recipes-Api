<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Users
        $users = [
            'Lewis Munyi' => 'lewis@email.com',
            'Agatha Bahati' => 'agatha@email.com',
        ];

        foreach ($users as $key => $value) {
            User::updateOrCreate([
                'email' => $value,
            ], [
                'name' => $key,
                'username' => Str::lower(explode(' ', $key)[0].'.'.explode(' ', $key)[1]),
                'email' => $value,
                'password' => bcrypt('secret'),
            ]);
        }

        for ($i = 0; $i < 1000; $i++) {
            $email = fake()->safeemail.$i;
            User::create([
                'name' => fake()->name,
                'username' => fake()->userName.$i,
                'email' => $email,
                'password' => bcrypt('secret'),
            ]);
            // User::updateOrCreate(
            //     [
            //         'email' => $email,
            //     ],
            //     [
            //         'name' => fake()->name,
            //         'username' => fake()->userName . $i,
            //         'email' => $email,
            //         'password' => bcrypt('secret'),
            //     ]
            // );
        }
    }
}
