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
                'description' => 'Manages the entire system with full access',
                'is_super_admin' => true,
                'is_admin' => true
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
                'name' => 'Cook',
                'slug' => 'cook',
                'description' => 'Manages meals and attendance',
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

        // Create roles
        foreach ($roles as $roleData) {
            Role::create($roleData);
        }

        // Create initial director (super admin) user
        $director = User::create([
            'name' => 'Director',
            'email' => 'director@intragest.com',
            'password' => Hash::make('password'),
            'status' => 'active'
        ]);
        $director->roles()->attach(Role::where('slug', 'director')->first());

        // Create initial boarding manager (admin) user
        $boardingManager = User::create([
            'name' => 'Boarding Manager',
            'email' => 'boarding@intragest.com',
            'password' => Hash::make('password'),
            'status' => 'active'
        ]);
        $boardingManager->roles()->attach(Role::where('slug', 'boarding-manager')->first());
    }
}
