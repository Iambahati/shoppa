<?php

namespace Database\Seeders;

use App\Enums\PermissionName;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PermissionName::cases() as $perm) {
            Permission::firstOrCreate(['name' => $perm->value]);
        }

        $this->command->info('Permissions seeded: ' . count(PermissionName::cases()) . ' entries');
    }
}