<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\Test;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_dapat_logout_dan_diarahkan_ke_homepage()
    {
        // Membuat user dan login
        $user = User::factory()->create();
        $this->actingAs($user);

        // Pastikan user sudah terautentikasi sebelum logout
        $this->assertAuthenticated();

        // Kirim request logout
        $response = $this->get(route('logout'));

        // Pastikan user telah logout
        $this->assertGuest();

        // Pastikan redirect ke halaman utama
        $response->assertRedirect('/');
    }
}
