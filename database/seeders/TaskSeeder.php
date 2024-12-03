<?php

namespace Database\Seeders;

use App\Models\CardModel;
use App\Models\SubTask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        // Create 10 tasks
        foreach (range(1, 10) as $index) {
             Task::create([
                'user_id' => $users->random()->id,
                'title' => fake()->sentence,
                'due_date' => fake()->date(),
            ]);
        }
    }
}
