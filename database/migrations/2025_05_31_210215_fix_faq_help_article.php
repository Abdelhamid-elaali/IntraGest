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
        // First, make sure the general category exists
        $category = HelpCategory::firstOrCreate(
            ['slug' => 'general'],
            [
                'name' => 'General Usage',
                'slug' => 'general',
                'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                'description' => 'General information and frequently asked questions',
                'order' => 3,
            ]
        );

        // Now create or update the faq article that's causing the 404
        HelpArticle::updateOrCreate(
            ['slug' => 'faq'],
            [
                'title' => 'Frequently Asked Questions',
                'slug' => 'faq',
                'excerpt' => 'Common questions and answers about using the system.',
                'content' => "# Frequently Asked Questions\n\n## General Questions\n\n**Q: How do I reset my password?**\nA: Go to your profile page and use the Account Security section to update your password.\n\n**Q: Can I customize my dashboard?**\nA: Currently, the dashboard layout is fixed, but we're working on customization options for a future update.\n\n**Q: How do I get help if I can't find an answer here?**\nA: Use the Contact Support form at the bottom of the Help Center to reach our support team.\n\n## Inventory Questions\n\n**Q: How do I create a new stock category?**\nA: Navigate to Stock Categories and use the \"Add New Category\" button. Fill in the name and select a color.\n\n**Q: Can I import inventory data from a spreadsheet?**\nA: Yes, use the Import function on the Stock Items page to upload a CSV file with your inventory data.\n\n**Q: How do I track low stock items?**\nA: The dashboard shows items below their minimum stock level, and you can also generate a Low Stock report.\n\n## User Management Questions\n\n**Q: How do I add a new staff member?**\nA: Go to Staff Management and click \"Add New Staff Member.\" Fill in their details and assign appropriate roles.\n\n**Q: Can I limit what certain users can access?**\nA: Yes, use the role-based permission system to control access to different parts of the application.",
                'is_published' => true,
                'view_count' => 15,
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
