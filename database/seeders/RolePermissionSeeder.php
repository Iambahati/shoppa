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
            RoleName::SuperAdmin->value => '*',

            RoleName::Admin->value => [
                PermissionName::ViewUsers->value,
                PermissionName::CreateUsers->value,
                PermissionName::EditUsers->value,
                PermissionName::ViewRoles->value,
                PermissionName::ManageRoles->value,
                PermissionName::ViewVendors->value,
                PermissionName::CreateVendors->value,
                PermissionName::EditVendors->value,
                PermissionName::ApproveVendors->value,
                PermissionName::ViewProducts->value,
                PermissionName::CreateProducts->value,
                PermissionName::EditProducts->value,
                PermissionName::DeleteProducts->value,
                PermissionName::ManageCategories->value,
                PermissionName::ViewOrders->value,
                PermissionName::ManageOrders->value,
                PermissionName::CancelOrders->value,
                PermissionName::ViewPayments->value,
                PermissionName::ManagePayments->value,
                PermissionName::ManageRefunds->value,
                PermissionName::ViewCustomerData->value,
                PermissionName::ManageDisputes->value,
                PermissionName::ManageSupportTickets->value,
                PermissionName::ManageShipments->value,
                PermissionName::ManageTheftReports->value,
                PermissionName::ViewInspections->value,
            ],

            RoleName::VendorManager->value => [
                PermissionName::ViewVendors->value,
                PermissionName::CreateVendors->value,
                PermissionName::EditVendors->value,
                PermissionName::ApproveVendors->value,
                PermissionName::ViewProducts->value,
                PermissionName::EditProducts->value,
                PermissionName::ViewOrders->value,
                PermissionName::ManageOrders->value,
            ],

            RoleName::Verifier->value => [
                PermissionName::ViewProducts->value,
                PermissionName::VerifyDevices->value,
                PermissionName::IssueCerts->value,
                PermissionName::ViewInspections->value,
                PermissionName::ManageTheftReports->value,
            ],

            RoleName::CustomerService->value => [
                PermissionName::ViewCustomerData->value,
                PermissionName::ViewOrders->value,
                PermissionName::ManageSupportTickets->value,
                PermissionName::ManageDisputes->value,
                PermissionName::ManageRefunds->value,
            ],

            RoleName::ContentManager->value => [
                PermissionName::ViewProducts->value,
                PermissionName::CreateProducts->value,
                PermissionName::EditProducts->value,
                PermissionName::ManageCategories->value,
                PermissionName::ContentManage->value,
            ],

            RoleName::Vendor->value => [
                PermissionName::ViewProducts->value,
                PermissionName::CreateProducts->value,
                PermissionName::EditProducts->value,
                PermissionName::DeleteProducts->value,
                PermissionName::ViewOrders->value,
                PermissionName::ManageOrders->value,
            ],

            RoleName::User->value => [
                PermissionName::ViewProducts->value,
            ],

            RoleName::Guest->value => [
                PermissionName::ViewProducts->value,
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
            $role = $roles->get($roleName);

            if (! $role) {
                $this->command->warn("Role [{$roleName}] not found — run RoleSeeder first.");
                continue;
            }

            $permsToAssign = $assigned === '*' ? $allPerms : collect($assigned)->map(
                fn(string $p) => $permissions->get($p)
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
