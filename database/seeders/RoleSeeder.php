<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Define roles with their permissions
        $roles = [
            [
                'name' => 'Director',
                'slug' => 'director',
                'description' => 'Full access to all system features',
                'is_super_admin' => true,
                'is_admin' => true,
                'permissions' => ['manage-staff', 'manage-users', 'manage-roles']
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrator with full access to all sections and functions',
                'is_super_admin' => false,
                'is_admin' => true,
                'permissions' => ['full-access']
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Manages staff and operations',
                'is_super_admin' => false,
                'is_admin' => true,
                'permissions' => ['manage-staff', 'manage-users']
            ],
            [
                'name' => 'Boarding Manager',
                'slug' => 'boarding-manager',
                'description' => 'Manages complaints, rooms, and payments',
                'is_super_admin' => false,
                'is_admin' => true
            ],
            [
                'name' => 'Stock Manager',
                'slug' => 'stock-manager',
                'description' => 'Oversees inventory and stock management',
                'is_super_admin' => false,
                'is_admin' => false
            ],
            [
                'name' => 'Intern',
                'slug' => 'intern',
                'description' => 'Can submit requests and complaints',
                'is_super_admin' => false,
                'is_admin' => false
            ]
        ];

        // Create roles if they don't exist
        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );
        }

        // Create initial director (super admin) user
        $directorEmail = 'director@intragest.com';
        // Delete existing director user if exists to avoid any issues
        User::where('email', $directorEmail)->delete();
        
        $director = User::create([
            'name' => 'Director',
            'email' => $directorEmail,
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'status' => 'active'
        ]);
        
        $directorRole = Role::where('slug', 'director')->first();
        if ($directorRole) {
            // Make sure to detach any existing roles first
            $director->roles()->detach();
            // Then attach the director role
            $director->roles()->attach($directorRole->id);
            $this->command->info("Director user created with email: {$directorEmail} and password: password");
        }

        // Create initial boarding manager (admin) user
        $boardingEmail = 'boarding@intragest.com';
        // Delete existing boarding manager user if exists to avoid any issues
        User::where('email', $boardingEmail)->delete();
        
        $boardingManager = User::create([
            'name' => 'Boarding Manager',
            'email' => $boardingEmail,
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'status' => 'active'
        ]);
        
        $boardingRole = Role::where('slug', 'boarding-manager')->first();
        if ($boardingRole) {
            // Make sure to detach any existing roles first
            $boardingManager->roles()->detach();
            // Then attach the boarding manager role
            $boardingManager->roles()->attach($boardingRole->id);
            $this->command->info("Boarding Manager user created with email: {$boardingEmail} and password: password");
        }
        
        // Create stock manager user
        $stockManagerEmail = 'stock@intragest.com';
        // Delete existing stock manager user if exists to avoid any issues
        User::where('email', $stockManagerEmail)->delete();
        
        $stockManager = User::create([
            'name' => 'Stock Manager',
            'email' => $stockManagerEmail,
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'status' => 'active'
        ]);
        
        $stockManagerRole = Role::where('slug', 'stock-manager')->first();
        if ($stockManagerRole) {
            // Make sure to detach any existing roles first
            $stockManager->roles()->detach();
            // Then attach the stock manager role
            $stockManager->roles()->attach($stockManagerRole->id);
            $this->command->info("Stock Manager user created with email: {$stockManagerEmail} and password: password");
        }

        // Create initial admin user
        $adminEmail = 'admin@intragest.com';
        User::where('email', $adminEmail)->delete();
        $admin = User::create([
            'name' => 'Admin',
            'email' => $adminEmail,
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
            'status' => 'active'
        ]);
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            $admin->roles()->detach();
            $admin->roles()->attach($adminRole->id);
            $this->command->info("Admin user created with email: {$adminEmail} and password: admin123");
        }
    }
}
