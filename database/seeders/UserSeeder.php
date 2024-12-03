<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        // Create 10 users
        foreach (range(1, 10) as $index) {
            User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail,
                'password' => bcrypt('password123'),
            ]);
        }

        User::create([
            'name' => 'admin@gmail.com',
            'email' => 'admin@gmail.com',
            'password' => bcrypt(1234567890)
        ]);
    }
}
