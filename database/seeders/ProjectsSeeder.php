<?php

namespace Database\Seeders;

use App\Models\ProjectModel;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        // Create 5 projects
        foreach (range(1, 5) as $index) {
            ProjectModel::create([
                'user_id' => $users->random()->id,
                'project_title' => fake()->sentence,
                'due_date' => fake()->date(),
            ]);
        }
    }
}
