<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_unverified_user_redirected_to_verify_notice(): void
    {
        $user = User::factory()->asBuyer()->unverified()->create();

        $this->actingAs($user)
            ->get(route('buyer.dashboard'))
            ->assertRedirect(route('verification.notice'));
    }

    public function test_verified_user_can_access_dashboard(): void
    {
        $user = User::factory()->asBuyer()->create();

        $this->actingAs($user)
            ->get(route('buyer.dashboard'))
            ->assertOk();
    }

    public function test_verification_link_verifies_user(): void
    {
        Event::fake();

        $user = User::factory()->asBuyer()->unverified()->create();

        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this->actingAs($user)->get($verifyUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }
}
