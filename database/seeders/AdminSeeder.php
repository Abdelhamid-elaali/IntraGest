<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin role if it doesn't exist
        $adminRole = Role::firstOrCreate(
            ['slug' => 'super-admin'],
            [
                'name' => 'Super Admin',
                'is_super_admin' => true,
                'is_admin' => true
            ]
        );

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@intragest.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'status' => 'active',
                'phone' => '123456789',
                'address' => 'Admin Address',
                'city' => 'Admin City'
            ]
        );

        // Assign admin role
        $admin->assignRole($adminRole);
    }
}
