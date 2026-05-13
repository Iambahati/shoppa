<?php

namespace Database\Seeders;

use App\Enums\PermissionName;
use App\Enums\RoleName;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * The permission matrix.
     *
     * '*' means every permission in PermissionName.
     * Otherwise list PermissionName cases by name (not value).
     */
    private function matrix(): array
    {
        return [
            RoleName::SuperAdmin => '*',

            RoleName::Admin => [
                PermissionName::ViewUsers,
                PermissionName::CreateUsers,
                PermissionName::EditUsers,
                PermissionName::ViewRoles,
                PermissionName::ManageRoles,
                PermissionName::ViewVendors,
                PermissionName::CreateVendors,
                PermissionName::EditVendors,
                PermissionName::ApproveVendors,
                PermissionName::ViewProducts,
                PermissionName::CreateProducts,
                PermissionName::EditProducts,
                PermissionName::DeleteProducts,
                PermissionName::ManageCategories,
                PermissionName::ViewOrders,
                PermissionName::ManageOrders,
                PermissionName::CancelOrders,
                PermissionName::ViewPayments,
                PermissionName::ManagePayments,
                PermissionName::ManageRefunds,
                PermissionName::ViewCustomerData,
                PermissionName::ManageDisputes,
                PermissionName::ManageSupportTickets,
                PermissionName::ManageShipments,
                PermissionName::ManageTheftReports,
                PermissionName::ViewInspections,
            ],

            RoleName::VendorManager => [
                PermissionName::ViewVendors,
                PermissionName::CreateVendors,
                PermissionName::EditVendors,
                PermissionName::ApproveVendors,
                PermissionName::ViewProducts,
                PermissionName::EditProducts,
                PermissionName::ViewOrders,
                PermissionName::ManageOrders,
            ],

            RoleName::Verifier => [
                PermissionName::ViewProducts,
                PermissionName::VerifyDevices,
                PermissionName::IssueCerts,
                PermissionName::ViewInspections,
                PermissionName::ManageTheftReports,
            ],

            RoleName::CustomerService => [
                PermissionName::ViewCustomerData,
                PermissionName::ViewOrders,
                PermissionName::ManageSupportTickets,
                PermissionName::ManageDisputes,
                PermissionName::ManageRefunds,
            ],

            RoleName::ContentManager => [
                PermissionName::ViewProducts,
                PermissionName::CreateProducts,
                PermissionName::EditProducts,
                PermissionName::ManageCategories,
                PermissionName::ContentManage,
            ],

            RoleName::Vendor => [
                PermissionName::ViewProducts,
                PermissionName::CreateProducts,
                PermissionName::EditProducts,
                PermissionName::DeleteProducts,
                PermissionName::ViewOrders,
                PermissionName::ManageOrders,
            ],

            RoleName::User => [
                PermissionName::ViewProducts,
            ],

            RoleName::Guest => [
                PermissionName::ViewProducts,
            ],
        ];
    }

    public function run(): void
    {
        // Wipe existing pivots so re-running is safe
        DB::table('role_permission')->truncate();

        $roles       = Role::all()->keyBy('name');
        $permissions = Permission::all()->keyBy('name');
        $allPerms    = $permissions->values();

        $inserts = [];

        foreach ($this->matrix() as $roleName => $assigned) {
            $role = $roles->get($roleName->value);

            if (! $role) {
                $this->command->warn("Role [{$roleName->value}] not found — run RoleSeeder first.");
                continue;
            }

            $permsToAssign = $assigned === '*' ? $allPerms : collect($assigned)->map(
                fn (PermissionName $p) => $permissions->get($p->value)
            )->filter();

            foreach ($permsToAssign as $perm) {
                $inserts[] = [
                    'role_id'       => $role->id,
                    'permission_id' => $perm->id,
                ];
            }
        }

        DB::table('role_permission')->insert($inserts);

        $this->command->info("Role–permission matrix seeded: {$this->countByRole($inserts)} assignments across " . count($this->matrix()) . ' roles.');
    }

    private function countByRole(array $inserts): int
    {
        return count($inserts);
    }
}