<?php

namespace Tests\Feature;

use App\Models\ProjectModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class KanbanTest extends TestCase
{
    public function test_ambil_data_project(): void
    {
        $user = User::factory()->create();
        ProjectModel::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->getJson(route('project.fetch', ['id' => $user->id]));

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }


    public function test_simpan_project(): void
    {
        $user = User::factory()->create();

        $data = [
            'project_title' => 'New Project',
            'due_date' => now()->addDays(7)->toDateString(),
            'user_id' => $user->id
        ];

        $response = $this->postJson('/api/project/store', $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['project_title' => 'New Project']);

        $this->assertDatabaseHas('project', $data);
    }

    public function test_hapus_project(): void
    {
        $project = ProjectModel::factory()->create();

        $response = $this->deleteJson(route('api.project.delete', ['id' => $project->id]));

        $response->assertStatus(200)
            ->assertJson(['message' => 'Delete Success']);

        $this->assertDatabaseMissing('project', ['id' => $project->id]);
    }

    public function test_gagal_ambil_data_project_jika_user_tidak_ada(): void
    {
        $response = $this->getJson(route('project.fetch', ['id' => 999]));

        $response->assertExactJson([])->assertStatus(200);
    }

    public function test_gagal_simpan_project_jika_data_tidak_lengkap(): void
    {
        $user = User::factory()->create();

        $data = [
            'project_title' => 'Proyek Baru',
            'user_id' => $user->id
        ];

        $response = $this->postJson('/api/project/store', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['due_date']);
    }

    public function test_gagal_simpan_project_jika_tidak_valid(): void
    {
        $data = [
            'due_date' => now()->addDays(7)->toDateString(),
            'user_id' => 999
        ];

        $response = $this->postJson('/api/project/store', $data);

        $response->assertStatus(422);
    }

}
