<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class RoleRedirectTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    private function loginAndGetRedirect(User $user): string
    {
        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        return $response->headers->get('Location') ?? '';
    }

    public function test_super_admin_redirects_to_admin_dashboard(): void
    {
        $user = User::factory()->asSuperAdmin()->create();
        $this->assertStringContainsString(route('admin.dashboard'), $this->loginAndGetRedirect($user));
    }

    public function test_admin_redirects_to_admin_dashboard(): void
    {
        $user = User::factory()->asAdmin()->create();
        $this->assertStringContainsString(route('admin.dashboard'), $this->loginAndGetRedirect($user));
    }

    public function test_vendor_manager_redirects_to_vendor_manager_dashboard(): void
    {
        $user = User::factory()->asVendorManager()->create();
        $this->assertStringContainsString(route('admin.vendor-manager.dashboard'), $this->loginAndGetRedirect($user));
    }

    public function test_verifier_redirects_to_verifier_dashboard(): void
    {
        $user = User::factory()->asVerifier()->create();
        $this->assertStringContainsString(route('verifier.dashboard'), $this->loginAndGetRedirect($user));
    }

    public function test_customer_service_redirects_to_cs_dashboard(): void
    {
        $user = User::factory()->asCustomerService()->create();
        $this->assertStringContainsString(route('admin.cs.dashboard'), $this->loginAndGetRedirect($user));
    }

    public function test_content_manager_redirects_to_content_dashboard(): void
    {
        $user = User::factory()->asContentManager()->create();
        $this->assertStringContainsString(route('admin.content.dashboard'), $this->loginAndGetRedirect($user));
    }

    public function test_vendor_redirects_to_vendor_dashboard(): void
    {
        $user = User::factory()->asVendor()->create();
        $this->assertStringContainsString(route('vendor.dashboard'), $this->loginAndGetRedirect($user));
    }

    public function test_buyer_redirects_to_buyer_dashboard(): void
    {
        $user = User::factory()->asBuyer()->create();
        $this->assertStringContainsString(route('buyer.dashboard'), $this->loginAndGetRedirect($user));
    }
}
