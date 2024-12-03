<?php

namespace Database\Seeders;

use App\Models\SubTask;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = Task::all();

        // Create 3 sub-tasks for each task
        foreach ($tasks as $task) {
            foreach (range(1, 3) as $index) {
                SubTask::create([
                    'task_id' => $task->id,
                    'tasks_name' => fake()->word,
                    'status' => 0,
                ]);
            }
        }
    }
}
