<?php 

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
         User::factory()
            ->vendor()
            ->create();
            
        User::factory(20)
        ->user()
        ->create();
        
        User::factory()
        ->superAdmin()
        ->create([
            'name'  => 'Admin User',
            'email' => 'admin@shoppa.com',
        ]);

        $superAdminRole = Role::where('name', 'Super Admin')->first();
        User::firstOrCreate(
            ['email' => 'admin@shoppa.co.ke'],
            [
                'name'              => 'Admin',
                'password'          => bcrypt('password'),
                'role_id'           => $superAdminRole->id,
                'email_verified_at' => now(),
            ]
        );
    }
}