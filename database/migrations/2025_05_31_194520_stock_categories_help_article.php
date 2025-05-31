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

        // Now create or update the stock-categories article that's causing the 404
        HelpArticle::updateOrCreate(
            ['slug' => 'stock-categories'],
            [
                'title' => 'Working with Categories',
                'slug' => 'stock-categories',
                'excerpt' => 'How to create and manage stock categories with the new color picker.',
                'content' => "# Working with Categories\n\n## Creating Categories\n\nThe category creation form has been updated with a modern interface:\n\n- Simplified form with clear input fields\n- New color picker with live preview\n- Color hex code display\n- Consistent button styling\n\n## Managing Categories\n\nThe category management interface allows you to:\n\n- View all categories in a clean, organized list\n- Edit category details easily\n- See color indicators for each category\n- Delete categories when needed (with confirmation)\n\n## Using Categories\n\nCategories help organize your inventory and can be used to:\n\n- Filter stock items\n- Generate reports by category\n- Visualize inventory distribution\n- Track category-specific metrics",
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
