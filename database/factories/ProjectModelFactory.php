<?php

namespace Database\Factories;

use App\Models\ProjectModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectModel>
 */
class ProjectModelFactory extends Factory
{
    protected $model = ProjectModel::class;

    public function definition()
    {
        return [
            'project_title' => $this->faker->sentence,
            'due_date' => $this->faker->date,
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
