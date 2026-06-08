<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_admin_can_access_dashboard(): void
    {
        $user = User::factory()->asAdmin()->create();
        $this->actingAs($user)->get(route('admin.dashboard'))->assertOk();
    }

    public function test_super_admin_can_access_dashboard(): void
    {
        $user = User::factory()->asSuperAdmin()->create();
        $this->actingAs($user)->get(route('admin.dashboard'))->assertOk();
    }

    public function test_vendor_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->asVendor()->create();
        $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_buyer_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->asBuyer()->create();
        $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_unauthenticated_redirected_to_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }
}
