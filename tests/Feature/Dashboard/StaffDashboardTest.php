<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use Tests\TestCase;

class StaffDashboardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_vendor_manager_can_access_dashboard(): void
    {
        $user = User::factory()->asVendorManager()->create();
        $this->actingAs($user)->get(route('admin.vendor-manager.dashboard'))->assertOk();
    }

    public function test_verifier_can_access_dashboard(): void
    {
        $user = User::factory()->asVerifier()->create();
        $this->actingAs($user)->get(route('verifier.dashboard'))->assertOk();
    }

    public function test_customer_service_can_access_dashboard(): void
    {
        $user = User::factory()->asCustomerService()->create();
        $this->actingAs($user)->get(route('admin.cs.dashboard'))->assertOk();
    }

    public function test_content_manager_can_access_dashboard(): void
    {
        $user = User::factory()->asContentManager()->create();
        $this->actingAs($user)->get(route('admin.content.dashboard'))->assertOk();
    }

    public function test_buyer_cannot_access_vendor_manager_dashboard(): void
    {
        $user = User::factory()->asBuyer()->create();
        $this->actingAs($user)->get(route('admin.vendor-manager.dashboard'))->assertForbidden();
    }

    public function test_buyer_cannot_access_verifier_dashboard(): void
    {
        $user = User::factory()->asBuyer()->create();
        $this->actingAs($user)->get(route('verifier.dashboard'))->assertForbidden();
    }

    public function test_buyer_cannot_access_cs_dashboard(): void
    {
        $user = User::factory()->asBuyer()->create();
        $this->actingAs($user)->get(route('admin.cs.dashboard'))->assertForbidden();
    }

    public function test_buyer_cannot_access_content_dashboard(): void
    {
        $user = User::factory()->asBuyer()->create();
        $this->actingAs($user)->get(route('admin.content.dashboard'))->assertForbidden();
    }
}
