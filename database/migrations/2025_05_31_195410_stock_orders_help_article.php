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

        // Now create or update the stock-orders article that's causing the 404
        HelpArticle::updateOrCreate(
            ['slug' => 'stock-orders'],
            [
                'title' => 'Creating and Managing Orders',
                'slug' => 'stock-orders',
                'excerpt' => 'How to work with the stock order system using the updated interface.',
                'content' => "# Creating and Managing Orders\n\n## Creating New Orders\n\nThe order creation process has been streamlined with:\n\n- Improved item selection interface\n- Dynamic calculations for subtotals and totals\n- Clear form layout with consistent styling\n- Modern buttons with helpful icons\n\n## Managing Existing Orders\n\nThe order management interface allows you to:\n\n- View order details in a clean, organized layout\n- Track order status with visual indicators\n- Update order information when needed\n- Generate order reports\n\n## Order Workflow\n\nThe typical order workflow includes:\n\n1. Creating a new order\n2. Adding items to the order\n3. Reviewing and confirming the order\n4. Processing the order\n5. Marking the order as complete",
                'is_published' => true,
                'view_count' => 6,
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
