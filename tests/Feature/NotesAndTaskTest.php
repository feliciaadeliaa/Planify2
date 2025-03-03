<?php

namespace Tests\Feature;

use App\Models\SubTask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotesAndTaskTest extends TestCase
{
    public function test_user_dapat_mengambil_data_dari_api(): void
    {
        $user = User::factory()->create();
        $note = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('notes.fetch', $user->id));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'user_id', 'title', 'due_date', 'created_at', 'sub_task']
        ]);
    }
    public function test_dapat_membuat_catatan()
    {
        $user = User::factory()->create();

        $data = [
            'title' => 'Catatan Tes',
            'due_date' => now()->addDays(7)->toDateString(),
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($user)->post(route('createTask'), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', $data);
    }
    public function test_tidak_bisa_mengambil_data_jika_user_belum_terdaftar()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('notes.fetch', 9999));

        $response->assertExactJson([]);
    }

    public function test_tidak_dapat_membuat_catatan_jika_tidak_lengkap()
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('createTask'), [
            'title' => 'Tanpa judul',
            'user_id' => $user->id, 
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['due_date']);
        ;
    }

    public function test_user_dapat_membuat_sub_tugas()
    {
        $user = User::factory()->create();
        $note = Task::factory()->create(['user_id' => $user->id]);

        $data = [
            'task_id' => $note->id,
            'tasks_name' => 'Sub-tugas 1',
        ];

        $response = $this->actingAs($user)->post('/api/create-sub-task', $data);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('sub_tasks', $data);
    }

    public function test_user_dapat_menghapus_tugas()
    {
        $user = User::factory()->create();
        $note = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete('/delete-task/' . $note->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('notes', ['id' => $note->id]);
    }

    public function test_gagal_menghapus_tugas_yang_tidak_ada()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete('/delete-task/9999');

        $response->assertStatus(422);
    }

    public function test_dapat_memperbarui_sub_tugas()
    {
        $user = User::factory()->create();
        $subtask = SubTask::factory()->create();

        $response = $this->actingAs($user)->put('/update-sub-task/' . $subtask->id, [
            'title' => 'Sub-tugas yang diperbarui'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('sub_tasks', ['id' => $subtask->id, 'title' => 'Sub-tugas yang diperbarui']);
    }

    public function test_gagal_memperbarui_sub_tugas_yang_tidak_ada()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/update-sub-task/9999', [
            'title' => 'Judul Baru'
        ]);

        $response->assertStatus(404);
    }

    public function test_dapat_menghapus_sub_tugas()
    {
        $user = User::factory()->create();
        $subtask = SubTask::factory()->create();

        $response = $this->actingAs($user)->delete('/delete-sub-tasks/' . $subtask->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('sub_tasks', ['id' => $subtask->id]);
    }

    public function test_gagal_menghapus_sub_tugas_yang_tidak_ada()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete('/delete-sub-tasks/9999');

        $response->assertStatus(404);
    }
}
