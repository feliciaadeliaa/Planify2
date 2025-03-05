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


class LoginTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_dapat_login_dengan_credential_yang_benar()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson(route('authenticate'), [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertStatus(200)->assertJson([
            'message' => 'Authenticated',
            'redirect' => '/dashboard',
        ]);
    }

    #[Test]
    public function user_tidak_dapat_login_dengan_password_salah()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson(route('authenticate'), [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
        $response->assertStatus(401)->assertJson([
            'message' => 'The provided credentials do not match our records.',
        ]);
    }
}
