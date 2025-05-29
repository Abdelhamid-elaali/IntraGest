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
        // Create director role if it doesn't exist
        $directorRole = Role::firstOrCreate(
            ['slug' => 'director'],
            [
                'name' => 'Director',
                'permissions' => json_encode([
                    'manage_rooms',
                    'manage_users',
                    'manage_payments',
                    'manage_stocks',
                    'manage_absences',
                    'view_reports',
                    'manage_settings'
                ]),
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

        // Assign director role
        $admin->assignRole($directorRole);
    }
}
