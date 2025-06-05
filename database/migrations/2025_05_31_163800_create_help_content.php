<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\HelpCategory;
use App\Models\HelpArticle;

class CreateHelpContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create categories if they don't exist
        $inventoryCategory = HelpCategory::firstOrCreate(
            ['slug' => 'inventory'],
            [
                'name' => 'Inventory Management',
                'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>',
                'description' => 'Learn how to manage inventory items, categories, and orders'
            ]
        );

        $userCategory = HelpCategory::firstOrCreate(
            ['slug' => 'users'],
            [
                'name' => 'User Management',
                'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>',
                'description' => 'User account management and permissions'
            ]
        );

        $generalCategory = HelpCategory::firstOrCreate(
            ['slug' => 'general'],
            [
                'name' => 'General Usage',
                'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                'description' => 'General information and frequently asked questions'
            ]
        );

        // Create articles
        // Inventory Management articles
        HelpArticle::firstOrCreate(
            ['slug' => 'inventory-management'],
            [
                'help_category_id' => $inventoryCategory->id,
                'title' => 'Inventory Management Guide',
                'excerpt' => 'Learn how to manage stock items, categories, and orders with the new Tailwind CSS interface.',
                'content' => "# Inventory Management Guide\n\nThis guide will help you understand how to use the new Tailwind CSS interface to manage your inventory effectively.\n\n## Stock Items\n\nThe stock items section allows you to add, edit, and manage all inventory items. The modernized interface includes:\n\n- Clear input placeholders for better guidance\n- Improved form layout with consistent styling\n- Enhanced validation feedback\n- Modern icons and buttons\n\n## Stock Categories\n\nCategories help you organize your inventory items. The updated interface includes:\n\n- A new color picker with live preview\n- Simplified category creation process\n- Better visual hierarchy\n\n## Stock Orders\n\nThe stock order system has been updated with:\n\n- Improved order item table\n- Dynamic calculations\n- Better form controls\n- Consistent button styling",
                'is_published' => true,
                'view_count' => 0,
            ]
        );

        HelpArticle::firstOrCreate(
            ['slug' => 'stock-items'],
            [
                'help_category_id' => $inventoryCategory->id,
                'title' => 'Managing Stock Items',
                'excerpt' => 'How to add, edit, and manage stock items in the inventory system.',
                'content' => "# Managing Stock Items\n\nThe stock items management interface has been updated with Tailwind CSS for a more modern and consistent experience.\n\n## Adding New Stock Items\n\nWhen adding a new stock item, you'll notice:\n\n- Clear input placeholders that guide you on what to enter\n- Improved form validation with clear error messages\n- Consistent button styling with helpful icons\n- Better spacing and layout for easier data entry\n\n## Editing Stock Items\n\nThe edit interface matches the new item interface for consistency, making it easy to update:\n\n- Item details\n- Pricing information\n- Stock levels\n- Category assignments\n\n## Stock Item List\n\nThe stock item list view provides a clear overview of all inventory with:\n\n- Sortable columns\n- Search functionality\n- Quick action buttons\n- Responsive design for all device sizes",
                'is_published' => true,
                'view_count' => 0,
            ]
        );

        HelpArticle::firstOrCreate(
            ['slug' => 'stock-categories'],
            [
                'help_category_id' => $inventoryCategory->id,
                'title' => 'Working with Categories',
                'excerpt' => 'How to create and manage stock categories with the new color picker.',
                'content' => "# Working with Categories\n\n## Creating Categories\n\nThe category creation form has been updated with a modern interface:\n\n- Simplified form with clear input fields\n- New color picker with live preview\n- Color hex code display\n- Consistent button styling\n\n## Managing Categories\n\nThe category management interface allows you to:\n\n- View all categories in a clean, organized list\n- Edit category details easily\n- See color indicators for each category\n- Delete categories when needed (with confirmation)\n\n## Using Categories\n\nCategories help organize your inventory and can be used to:\n\n- Filter stock items\n- Generate reports by category\n- Visualize inventory distribution\n- Track category-specific metrics",
                'is_published' => true,
                'view_count' => 0,
            ]
        );

        HelpArticle::firstOrCreate(
            ['slug' => 'stock-orders'],
            [
                'help_category_id' => $inventoryCategory->id,
                'title' => 'Creating and Managing Orders',
                'excerpt' => 'How to work with the stock order system using the updated interface.',
                'content' => "# Creating and Managing Orders\n\n## Creating New Orders\n\nThe order creation process has been streamlined with:\n\n- Improved item selection interface\n- Dynamic calculations for subtotals and totals\n- Clear form layout with consistent styling\n- Modern buttons with helpful icons\n\n## Managing Existing Orders\n\nThe order management interface allows you to:\n\n- View order details in a clean, organized layout\n- Track order status with visual indicators\n- Update order information when needed\n- Generate order reports\n\n## Order Workflow\n\nThe typical order workflow includes:\n\n1. Creating a new order\n2. Adding items to the order\n3. Reviewing and confirming the order\n4. Processing the order\n5. Marking the order as complete",
                'is_published' => true,
                'view_count' => 0,
            ]
        );

        // User Management articles
        HelpArticle::firstOrCreate(
            ['slug' => 'user-profiles'],
            [
                'help_category_id' => $userCategory->id,
                'title' => 'Managing Your Profile',
                'excerpt' => 'Learn how to update your profile information and change your password.',
                'content' => "# Managing Your Profile\n\n## Account Information\n\nThe profile page allows you to manage your personal information:\n\n- Update your name, email, and other details\n- View your account status and role\n- Manage notification preferences\n\n## Password Security\n\nThe password management section has been improved with:\n\n- Clear input fields with helpful placeholders\n- Strong password requirements\n- Confirmation field to prevent typos\n- Secure password handling\n\n## Profile Settings\n\nAdditional profile settings allow you to customize your experience:\n\n- Interface preferences\n- Default views\n- Regional settings (date format, time zone, etc.)",
                'is_published' => true,
                'view_count' => 0,
            ]
        );

        HelpArticle::firstOrCreate(
            ['slug' => 'staff-management'],
            [
                'help_category_id' => $userCategory->id,
                'title' => 'Managing Staff Members',
                'excerpt' => 'How to add, edit, and manage staff accounts in the system.',
                'content' => "# Managing Staff Members\n\n## Adding New Staff\n\nThe staff creation form has been updated with:\n\n- Clear input fields with helpful placeholders\n- Role selection dropdown\n- Permission assignment options\n- Secure password creation\n\n## Editing Staff Accounts\n\nThe staff edit interface allows administrators to:\n\n- Update staff information\n- Change role assignments\n- Adjust permissions\n- Reset passwords if needed\n\n## Staff Directory\n\nThe staff directory provides a comprehensive view of all staff members with:\n\n- Searchable and sortable list\n- Quick filters by role or department\n- Contact information\n- Status indicators",
                'is_published' => true,
                'view_count' => 0,
            ]
        );

        HelpArticle::firstOrCreate(
            ['slug' => 'permissions'],
            [
                'help_category_id' => $userCategory->id,
                'title' => 'Roles and Permissions',
                'excerpt' => 'Understanding the role-based permission system.',
                'content' => "# Roles and Permissions\n\n## Role System\n\nThe application uses a role-based access control system:\n\n- Administrators have full system access\n- Managers have access to most features except system configuration\n- Staff members have limited access based on their assigned areas\n- Guests have minimal read-only access\n\n## Permission Management\n\nPermissions can be assigned at both the role and individual user levels:\n\n- Role-based permissions apply to all users with that role\n- Individual permissions can override role defaults\n- Granular control over specific actions (view, create, edit, delete)\n\n## Security Best Practices\n\nWhen managing permissions, follow these guidelines:\n\n- Assign the minimum necessary permissions\n- Regularly review user access\n- Remove permissions when no longer needed\n- Use role-based assignments when possible",
                'is_published' => true,
                'view_count' => 0,
            ]
        );

        // General Usage articles
        HelpArticle::firstOrCreate(
            ['slug' => 'supplier-management'],
            [
                'help_category_id' => $generalCategory->id,
                'title' => 'Supplier Management',
                'excerpt' => 'How to add, edit and manage suppliers with the modernized interface.',
                'content' => "# Supplier Management\n\n## Adding New Suppliers\n\nThe supplier creation interface has been updated with:\n\n- Clear form fields with helpful placeholders\n- Contact information section\n- Address formatting\n- Notes and additional details\n\n## Managing Suppliers\n\nThe supplier management interface allows you to:\n\n- View all suppliers in a clean, organized list\n- Search and filter suppliers\n- Edit supplier details\n- Track supplier relationships\n\n## Supplier Orders\n\nLink suppliers to orders for better tracking:\n\n- Associate orders with specific suppliers\n- Track supplier performance\n- Manage supplier-specific pricing\n- Generate supplier reports",
                'is_published' => true,
                'view_count' => 0,
            ]
        );

        HelpArticle::firstOrCreate(
            ['slug' => 'ui-guide'],
            [
                'help_category_id' => $generalCategory->id,
                'title' => 'New UI Guide',
                'excerpt' => 'Guide to the new Tailwind CSS interface and design elements.',
                'content' => "# New UI Guide\n\n## Tailwind CSS Interface\n\nThe application has been updated with a modern Tailwind CSS interface:\n\n- Clean, consistent design across all pages\n- Improved responsiveness for all device sizes\n- Better accessibility features\n- Modern form controls and buttons\n\n## Design Elements\n\nKey design elements include:\n\n- Consistent color scheme based on the blue palette\n- Clear typography with improved readability\n- Modern icons for better visual cues\n- Improved spacing and layout\n\n## Form Improvements\n\nAll forms have been enhanced with:\n\n- Clear input placeholders\n- Consistent validation styling\n- Helpful error messages\n- Improved button placement and styling",
                'is_published' => true,
                'view_count' => 0,
            ]
        );

        HelpArticle::firstOrCreate(
            ['slug' => 'getting-started'],
            [
                'help_category_id' => $generalCategory->id,
                'title' => 'Getting Started',
                'excerpt' => 'A quick introduction to the IntraGest system for new users.',
                'content' => "# Getting Started with IntraGest\n\n## System Overview\n\nIntraGest is a comprehensive management system that includes:\n\n- Inventory management\n- Supplier management\n- Staff management\n- Reporting tools\n\n## First Steps\n\n1. **Update Your Profile**: Customize your account settings and password\n2. **Explore the Dashboard**: Familiarize yourself with the main dashboard and navigation\n3. **Review Help Resources**: Browse through the help center articles for detailed guidance\n\n## Key Features\n\n- **Inventory Tracking**: Manage stock items, categories, and orders\n- **User Management**: Control access with role-based permissions\n- **Supplier Directory**: Maintain supplier information and relationships\n- **Reporting**: Generate insights from system data",
                'is_published' => true,
                'view_count' => 0,
            ]
        );

        HelpArticle::firstOrCreate(
            ['slug' => 'faq'],
            [
                'help_category_id' => $generalCategory->id,
                'title' => 'Frequently Asked Questions',
                'excerpt' => 'Common questions and answers about using the system.',
                'content' => "# Frequently Asked Questions\n\n## General Questions\n\n**Q: How do I reset my password?**\nA: Go to your profile page and use the Account Security section to update your password.\n\n**Q: Can I customize my dashboard?**\nA: Currently, the dashboard layout is fixed, but we're working on customization options for a future update.\n\n**Q: How do I get help if I can't find an answer here?**\nA: Use the Contact Support form at the bottom of the Help Center to reach our support team.\n\n## Inventory Questions\n\n**Q: How do I create a new stock category?**\nA: Navigate to Stock Categories and use the \"Add New Category\" button. Fill in the name and select a color.\n\n**Q: Can I import inventory data from a spreadsheet?**\nA: Yes, use the Import function on the Stock Items page to upload a CSV file with your inventory data.\n\n**Q: How do I track low stock items?**\nA: The dashboard shows items below their minimum stock level, and you can also generate a Low Stock report.\n\n## User Management Questions\n\n**Q: How do I add a new staff member?**\nA: Go to Staff Management and click \"Add New Staff Member.\" Fill in their details and assign appropriate roles.\n\n**Q: Can I limit what certain users can access?**\nA: Yes, use the role-based permission system to control access to different parts of the application.",
                'is_published' => true,
                'view_count' => 0,
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This is a data migration, so we'll just leave the data in place
        // If you want to remove the data, uncomment the following lines
        
        /*
        // Get the article slugs we created
        $articleSlugs = [
            'inventory-management', 'stock-items', 'stock-categories', 'stock-orders',
            'user-profiles', 'staff-management', 'permissions',
            'supplier-management', 'ui-guide', 'getting-started', 'faq'
        ];
        
        // Delete the articles
        HelpArticle::whereIn('slug', $articleSlugs)->delete();
        
        // Get the category slugs we created
        $categorySlugs = ['inventory', 'users', 'general'];
        
        // Delete the categories
        HelpCategory::whereIn('slug', $categorySlugs)->delete();
        */
    }
}
