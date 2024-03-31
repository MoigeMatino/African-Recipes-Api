<?php

namespace Database\Seeders;

use Illuminate\Support\Str;

use App\Models\User;
use Illuminate\Database\Seeder;

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
                'username' => Str::lower(explode(' ', $key)[0] . "." . explode(' ', $key)[1]),
                'email' => $value,
                'password' => bcrypt('secret'),
            ]);
        }

        for ($i = 0; $i < 10; $i++) {
            User::updateOrCreate(
                [
                    'email' => fake()->email,
                ],
                [
                    'name' => fake()->name,
                    'username' => fake()->userName,
                    'email' => fake()->safeemail,
                    'password' => bcrypt('secret'),
                ]
            );
        }
    }
}
