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
        // First, make sure the inventory category exists
        $category = HelpCategory::firstOrCreate(
            ['slug' => 'inventory'],
            [
                'name' => 'Inventory Management',
                'slug' => 'inventory',
                'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>',
                'description' => 'Learn how to manage inventory items, categories, and orders',
                'order' => 1,
            ]
        );

        // Now create or update the stock-items article that's causing the 404
        HelpArticle::updateOrCreate(
            ['slug' => 'stock-items'],
            [
                'title' => 'Managing Stock Items',
                'slug' => 'stock-items',
                'excerpt' => 'How to add, edit, and manage stock items in the inventory system.',
                'content' => "# Managing Stock Items\n\nThe stock items management interface has been updated with Tailwind CSS for a more modern and consistent experience.\n\n## Adding New Stock Items\n\nWhen adding a new stock item, you'll notice:\n\n- Clear input placeholders that guide you on what to enter\n- Improved form validation with clear error messages\n- Consistent button styling with helpful icons\n- Better spacing and layout for easier data entry\n\n## Editing Stock Items\n\nThe edit interface matches the new item interface for consistency, making it easy to update:\n\n- Item details\n- Pricing information\n- Stock levels\n- Category assignments\n\n## Stock Item List\n\nThe stock item list view provides a clear overview of all inventory with:\n\n- Sortable columns\n- Search functionality\n- Quick action buttons\n- Responsive design for all device sizes",
                'is_published' => true,
                'view_count' => 12,
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
