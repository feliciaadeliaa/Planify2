<?php

namespace Tests\Feature;

use App\Models\MessageModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageTest extends TestCase
{
    public function test_user_bisa_dapat_data_pesan_dari_api()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/chat/send', [
            'project_id' => 1,
            'message' => 'New Test Message',
            'username' => 'NewUser',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('messages', [
            'message' => 'New Test Message',
        ]);
    }

    public function test_can_store_message()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/chat/send', [
            'project_id' => 1,
            'message' => 'New Test Message',
            'username' => 'NewUser',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('messages', [
            'message' => 'New Test Message',
        ]);
    }
}
