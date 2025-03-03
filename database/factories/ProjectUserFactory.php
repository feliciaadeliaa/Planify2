<?php

namespace Database\Factories;

use App\Models\ProjectModel;
use App\Models\ProjectUserModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectUserModel>
 */
class ProjectUserFactory extends Factory
{
    protected $model = ProjectUserModel::class;
    public function definition(): array
    {
        
        return [
            'project_id' => ProjectModel::factory(), // Buat project baru
            'from' => $this->faker->email, // Menggunakan email random
            'to_user_id' => User::factory(), // Buat user baru
            'status' => $this->faker->randomElement([0, 1, 2]), // Contoh status random
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
