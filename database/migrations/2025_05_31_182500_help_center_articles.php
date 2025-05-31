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

        // Now create the specific article that's causing the 404
        HelpArticle::updateOrCreate(
            ['slug' => 'inventory-management'],
            [
                'title' => 'Inventory Management Guide',
                'slug' => 'inventory-management',
                'excerpt' => 'Learn how to manage stock items, categories, and orders with the new Tailwind CSS interface.',
                'content' => "# Inventory Management Guide\n\nThis guide will help you understand how to use the new Tailwind CSS interface to manage your inventory effectively.\n\n## Stock Items\n\nThe stock items section allows you to add, edit, and manage all inventory items. The modernized interface includes:\n\n- Clear input placeholders for better guidance\n- Improved form layout with consistent styling\n- Enhanced validation feedback\n- Modern icons and buttons\n\n## Stock Categories\n\nCategories help you organize your inventory items. The updated interface includes:\n\n- A new color picker with live preview\n- Simplified category creation process\n- Better visual hierarchy\n\n## Stock Orders\n\nThe stock order system has been updated with:\n\n- Improved order item table\n- Dynamic calculations\n- Better form controls\n- Consistent button styling",
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
