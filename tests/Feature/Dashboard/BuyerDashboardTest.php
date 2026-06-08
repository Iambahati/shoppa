<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use Tests\TestCase;

class BuyerDashboardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_buyer_can_access_dashboard(): void
    {
        $user = User::factory()->asBuyer()->create();
        $this->actingAs($user)->get(route('buyer.dashboard'))->assertOk();
    }

    public function test_unauthenticated_redirected_to_login(): void
    {
        $this->get(route('buyer.dashboard'))->assertRedirect(route('login'));
    }
}
