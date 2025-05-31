<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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

        // Delete existing admin user if exists to avoid any issues
        User::where('email', 'admin@intragest.com')->delete();
        
        // Create admin user with fresh credentials
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@intragest.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
            'status' => 'active',
            'phone' => '123456789',
            'address' => 'Admin Address',
            'city' => 'Admin City'
        ]);

        // Make sure we have the user created
        $this->command->info('Admin user created with email: admin@intragest.com and password: admin123');
        
        // Check if assignRole method exists
        if (method_exists($admin, 'assignRole')) {
            // Assign director role
            $admin->assignRole($directorRole);
        } else {
            // Direct assignment if no assignRole method
            $admin->role_id = $directorRole->id;
            $admin->save();
        }
    }
}
