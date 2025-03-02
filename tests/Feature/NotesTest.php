<?php

namespace Tests\Feature;

use App\Models\SubTask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class NotesTest extends TestCase
{

    #[Test]
    public function users_can_create_new_notes(): void
    {
        // check if users exist
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password')
            ]);
        }

        // insert to database
        $noteData = [
            'title' => 'Test Note',
            'due_date' => '2025-03-15',
            'user_id' => $user->id
        ];
        $response = $this->postJson('/api/create-notes', $noteData);

        // check response & exist in database
        $response->assertStatus(200)
            ->assertJson(['message' => 'success']);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Note',
            'due_date' => '2025-03-15',
            'user_id' => $user->id
        ]);

    }

    #[Test]
    public function test_user_can_fetch_tasks()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        Task::create([
            'title' => 'Test Task',
            'due_date' => '2025-03-10',
            'user_id' => $user->id,
        ]);

        $this->getJson("/api/tasks/fetch/{$user->id}")
            ->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonStructure([['id', 'title', 'due_date', 'user_id']]);
    }


    #[Test]
    public function test_user_can_delete_task()
    {

    }

    #[Test]
    public function test_user_can_update_subtask_status()
    {

    }

    #[Test]
    public function test_user_can_delete_subtask()
    {

    }
}
