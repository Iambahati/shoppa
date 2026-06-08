<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    private function adminRole(): string
    {
        return Role::where('name', 'Admin')->value('name');
    }

    public function test_admin_can_list_users(): void
    {
        $admin = User::factory()->asAdmin()->create();
        $this->actingAs($admin)->get(route('admin.users.index'))->assertOk();
    }

    public function test_admin_can_access_create_form(): void
    {
        $admin = User::factory()->asAdmin()->create();
        $this->actingAs($admin)->get(route('admin.users.create'))->assertOk();
    }

    public function test_admin_can_create_user(): void
    {
        $admin = User::factory()->asAdmin()->create();

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name'     => 'New Staff Member',
            'email'    => 'staff@example.com',
            'password' => 'Password1',
            'role'     => 'Verifier',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'staff@example.com']);
    }

    public function test_admin_create_user_with_invalid_data_fails(): void
    {
        $admin = User::factory()->asAdmin()->create();

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name'     => '',
            'email'    => 'not-an-email',
            'password' => 'short',
            'role'     => 'nonexistent-role',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'role']);
    }

    public function test_admin_can_view_user(): void
    {
        $admin  = User::factory()->asAdmin()->create();
        $target = User::factory()->asBuyer()->create();

        $this->actingAs($admin)->get(route('admin.users.show', $target))->assertOk();
    }

    public function test_admin_can_access_edit_form(): void
    {
        $admin  = User::factory()->asAdmin()->create();
        $target = User::factory()->asBuyer()->create();

        $this->actingAs($admin)->get(route('admin.users.edit', $target))->assertOk();
    }

    public function test_admin_can_update_user(): void
    {
        $admin  = User::factory()->asAdmin()->create();
        $target = User::factory()->asBuyer()->create();

        $response = $this->actingAs($admin)->put(route('admin.users.update', $target), [
            'name'  => 'Updated Name',
            'phone' => null,
            'role'  => 'Admin',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['id' => $target->id, 'name' => 'Updated Name']);
    }

    public function test_admin_can_soft_delete_user(): void
    {
        $admin  = User::factory()->asAdmin()->create();
        $target = User::factory()->asBuyer()->create();

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $target));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertSoftDeleted('users', ['id' => $target->id]);
    }

    public function test_vendor_cannot_access_user_list(): void
    {
        $vendor = User::factory()->asVendor()->create();
        $this->actingAs($vendor)->get(route('admin.users.index'))->assertForbidden();
    }

    public function test_buyer_cannot_access_user_list(): void
    {
        $buyer = User::factory()->asBuyer()->create();
        $this->actingAs($buyer)->get(route('admin.users.index'))->assertForbidden();
    }
}
