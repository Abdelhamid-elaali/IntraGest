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

        // Now create or update the ui-guide article that's causing the 404
        HelpArticle::updateOrCreate(
            ['slug' => 'ui-guide'],
            [
                'title' => 'New UI Guide',
                'slug' => 'ui-guide',
                'excerpt' => 'Guide to the new Tailwind CSS interface and design elements.',
                'content' => "# New UI Guide\n\n## Tailwind CSS Interface\n\nThe application has been updated with a modern Tailwind CSS interface:\n\n- Clean, consistent design across all pages\n- Improved responsiveness for all device sizes\n- Better accessibility features\n- Modern form controls and buttons\n\n## Design Elements\n\nKey design elements include:\n\n- Consistent color scheme based on the blue palette\n- Clear typography with improved readability\n- Modern icons for better visual cues\n- Improved spacing and layout\n\n## Form Improvements\n\nAll forms have been enhanced with:\n\n- Clear input placeholders\n- Consistent validation styling\n- Helpful error messages\n- Improved button placement and styling",
                'is_published' => true,
                'view_count' => 8,
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
