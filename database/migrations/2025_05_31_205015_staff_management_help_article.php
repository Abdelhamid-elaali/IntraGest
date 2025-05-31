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

        // Now create or update the staff-management article that's causing the 404
        HelpArticle::updateOrCreate(
            ['slug' => 'staff-management'],
            [
                'title' => 'Managing Staff Members',
                'slug' => 'staff-management',
                'excerpt' => 'How to add, edit, and manage staff accounts in the system.',
                'content' => "# Managing Staff Members\n\n## Adding New Staff\n\nThe staff creation form has been updated with:\n\n- Clear input fields with helpful placeholders\n- Role selection dropdown\n- Permission assignment options\n- Secure password creation\n\n## Editing Staff Accounts\n\nThe staff edit interface allows administrators to:\n\n- Update staff information\n- Change role assignments\n- Adjust permissions\n- Reset passwords if needed\n\n## Staff Directory\n\nThe staff directory provides a comprehensive view of all staff members with:\n\n- Searchable and sortable list\n- Quick filters by role or department\n- Contact information\n- Status indicators",
                'is_published' => true,
                'view_count' => 7,
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
