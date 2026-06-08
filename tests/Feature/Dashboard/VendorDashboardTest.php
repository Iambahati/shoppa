<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use Tests\TestCase;

class VendorDashboardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_vendor_can_access_dashboard(): void
    {
        $user = User::factory()->asVendor()->create();
        $this->actingAs($user)->get(route('vendor.dashboard'))->assertOk();
    }

    public function test_buyer_cannot_access_vendor_dashboard(): void
    {
        $user = User::factory()->asBuyer()->create();
        $this->actingAs($user)->get(route('vendor.dashboard'))->assertForbidden();
    }

    public function test_admin_cannot_access_vendor_dashboard(): void
    {
        $user = User::factory()->asAdmin()->create();
        $this->actingAs($user)->get(route('vendor.dashboard'))->assertForbidden();
    }

    public function test_unauthenticated_redirected_to_login(): void
    {
        $this->get(route('vendor.dashboard'))->assertRedirect(route('login'));
    }
}
