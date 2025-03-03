<?php

namespace Database\Factories;

use App\Models\SubTask;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubTask>
 */
class SubTaskFactory extends Factory
{
    protected $model = SubTask::class;

    public function definition(): array
    {
        return [
            'task_id' => Task::factory(), // Membuat task baru jika tidak ada
            'tasks_name' => $this->faker->sentence(3),
            'status' => $this->faker->randomElement([0, 1]), // 0 = belum selesai, 1 = selesai
        ];
    }
}
