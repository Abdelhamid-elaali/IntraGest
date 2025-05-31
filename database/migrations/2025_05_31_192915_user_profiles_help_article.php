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

        // Now create or update the user-profiles article that's causing the 404
        HelpArticle::updateOrCreate(
            ['slug' => 'user-profiles'],
            [
                'title' => 'Managing Your Profile',
                'slug' => 'user-profiles',
                'excerpt' => 'Learn how to update your profile information and change your password.',
                'content' => "# Managing Your Profile\n\n## Account Information\n\nThe profile page allows you to manage your personal information:\n\n- Update your name, email, and other details\n- View your account status and role\n- Manage notification preferences\n\n## Password Security\n\nThe password management section has been improved with:\n\n- Clear input fields with helpful placeholders\n- Strong password requirements\n- Confirmation field to prevent typos\n- Secure password handling\n\n## Profile Settings\n\nAdditional profile settings allow you to customize your experience:\n\n- Interface preferences\n- Default views\n- Regional settings (date format, time zone, etc.)",
                'is_published' => true,
                'view_count' => 10,
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
