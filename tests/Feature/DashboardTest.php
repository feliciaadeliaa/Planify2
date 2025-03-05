<?php

namespace Tests\Feature;

use App\Models\ProjectModel;
use App\Models\SubTask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh --seed');
    }
    public function test_mendapatakan_data_dashboard()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Buat data manual tanpa factory
        Task::create(['user_id' => $user->id, 'title' => 'Task 1', 'due_date' => now()->addDays(7)]);
        SubTask::create(['tasks_name' => 'adawda', 'task_id' => 1, 'status' => 2]);
        ProjectModel::create(['project_title' => 'nama project', 'user_id' => $user->id, 'due_date' => now()->addDays(4), 'status' => 'personal']);

        $response = $this->get('/dashboard');

        $response->assertStatus(200)
            ->assertInertia(
                fn($page) =>
                $page->has('totalTask')
                    ->has('task')
                    ->has('totalSubTask')
                    ->has('projectStatistics')
            );
    }

    public function test_data_statistik()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Task::create(['user_id' => $user->id, 'title' => 'Task 1', 'due_date' => now()->addDays(7)]);
        SubTask::create(['tasks_name' => 'adawda', 'task_id' => 1, 'status' => 2]);
        ProjectModel::create(['project_title' => 'nama project', 'user_id' => $user->id, 'due_date' => now()->addDays(4), 'status' => 'personal']);

        $response = $this->get('/api/task-statistics');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'totalTasks',
                'totalSubTasks',
                'statusCounts'
            ]);
    }

    public function test_ambil_data__users()
    {
        User::create(['name' => 'User 1', 'email' => 'user1@example.com', 'password' => bcrypt('password')]);
        User::create(['name' => 'User 2', 'email' => 'user2@example.com', 'password' => bcrypt('password')]);

        $response = $this->get(route('fetchUsers'));

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_tidak_dapat_akses_dashboard_jika_belum_login()
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }
}
