<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_forgot_password_page_is_accessible(): void
    {
        $this->get('/forgot-password')->assertStatus(200);
    }

    public function test_reset_link_sent_for_valid_email(): void
    {
        Notification::fake();

        $user = User::factory()->asBuyer()->create();

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        $response->assertSessionHas('status');
        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_link_request_with_unknown_email_returns_error(): void
    {
        $response = $this->post('/forgot-password', ['email' => 'nobody@example.com']);

        $response->assertSessionHasErrors('email');
    }

    public function test_password_reset_with_valid_token_updates_password(): void
    {
        Notification::fake();

        $user = User::factory()->asBuyer()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token'                 => $notification->token,
                'email'                 => $user->email,
                'password'              => 'newpassword1',
                'password_confirmation' => 'newpassword1',
            ]);

            $response->assertRedirect(route('login'));
            return true;
        });
    }

    public function test_password_reset_with_invalid_token_fails(): void
    {
        $user = User::factory()->asBuyer()->create();

        $response = $this->post('/reset-password', [
            'token'                 => 'invalid-token',
            'email'                 => $user->email,
            'password'              => 'newpassword1',
            'password_confirmation' => 'newpassword1',
        ]);

        $response->assertSessionHasErrors('email');
    }
}
