<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class LoginTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_valid_credentials_authenticate_user(): void
    {
        $user = User::factory()->asBuyer()->create();

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect();
    }

    public function test_invalid_password_returns_validation_error(): void
    {
        $user = User::factory()->asBuyer()->create();

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_nonexistent_email_returns_validation_error(): void
    {
        $response = $this->post('/login', [
            'email'    => 'nobody@example.com',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_remember_me_sets_cookie(): void
    {
        $user = User::factory()->asBuyer()->create();

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
            'remember' => true,
        ]);

        $response->assertCookie(auth()->guard()->getRecallerName());
    }
}
