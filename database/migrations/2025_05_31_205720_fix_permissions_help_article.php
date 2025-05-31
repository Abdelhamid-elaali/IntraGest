<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\HelpCategory;
use App\Models\HelpArticle;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, make sure the users category exists
        $category = HelpCategory::firstOrCreate(
            ['slug' => 'users'],
            [
                'name' => 'User Management',
                'slug' => 'users',
                'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>',
                'description' => 'User account management and permissions',
                'order' => 2,
            ]
        );

        // Now create or update the permissions article that's causing the 404
        HelpArticle::updateOrCreate(
            ['slug' => 'permissions'],
            [
                'title' => 'Roles and Permissions',
                'slug' => 'permissions',
                'excerpt' => 'Understanding the role-based permission system.',
                'content' => "# Roles and Permissions\n\n## Role System\n\nThe application uses a role-based access control system:\n\n- Administrators have full system access\n- Managers have access to most features except system configuration\n- Staff members have limited access based on their assigned areas\n- Guests have minimal read-only access\n\n## Permission Management\n\nPermissions can be assigned at both the role and individual user levels:\n\n- Role-based permissions apply to all users with that role\n- Individual permissions can override role defaults\n- Granular control over specific actions (view, create, edit, delete)\n\n## Security Best Practices\n\nWhen managing permissions, follow these guidelines:\n\n- Assign the minimum necessary permissions\n- Regularly review user access\n- Remove permissions when no longer needed\n- Use role-based assignments when possible",
                'is_published' => true,
                'view_count' => 5,
                'help_category_id' => $category->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to remove anything in down method
    }
};
