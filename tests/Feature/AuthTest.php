<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_resident_can_register_and_login(): void
    {
        $response = $this->post('/register', [
            'name' => 'Juan Dela Cruz',
            'email' => 'juan@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/resident/dashboard');
        $this->assertAuthenticated();

        $user = User::where('email', 'juan@example.com')->first();
        $this->assertNotNull($user);
        $this->assertAuthenticatedAs($user);

        $this->post('/logout');
        $this->assertGuest();

        $loginResponse = $this->post('/login', [
            'email' => 'juan@example.com',
            'password' => 'password123',
        ]);

        $loginResponse->assertRedirect('/resident/dashboard');
        $this->assertAuthenticatedAs($user);
    }
}
