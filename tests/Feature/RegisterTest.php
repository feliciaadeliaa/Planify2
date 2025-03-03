<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class RegisterTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate'); // Jalankan ulang migrasi sebelum setiap pengujian
    }

    #[Test]
    public function user_dapat_register_dengan_data_valid()
    {
        $response = $this->postJson(route('register.create'), [
            'name' => 'Test User',
            'email' => 'test' . uniqid() . '@example.com', // Gunakan email unik untuk menghindari duplikasi
            'password' => 'password',
        ]);

        // Pastikan response sukses dengan status 201 (Created)
        $response->assertStatus(201)->assertJson([
            'message' => 'Registered successfully',
            'redirect' => '/login',
        ]);
    }

    #[Test]
    public function user_tidak_dapat_register_dengan_email_yang_sudah_terdaftar()
    {
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->postJson(route('register.create'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        // Pastikan validasi gagal dengan status 422
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }
}
