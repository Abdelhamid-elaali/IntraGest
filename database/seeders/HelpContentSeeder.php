<?php

namespace Database\Seeders;

use App\Models\HelpCategory;
use App\Models\HelpArticle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HelpContentSeeder extends Seeder
{
    public function run()
    {
        // Create Categories
        $categories = [
            [
                'name' => 'Getting Started',
                'slug' => 'getting-started',
                'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>',
                'description' => 'Basic information about using IntraGest',
                'order' => 1,
            ],
            [
                'name' => 'Absence Management',
                'slug' => 'absence-management',
                'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>',
                'description' => 'Learn about managing absences and leave requests',
                'order' => 2,
            ],
            [
                'name' => 'Payment System',
                'slug' => 'payment-system',
                'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>',
                'description' => 'Information about payments and billing',
                'order' => 3,
            ],
            [
                'name' => 'Stock Management',
                'slug' => 'stock-management',
                'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>',
                'description' => 'Guide to managing inventory and stock',
                'order' => 4,
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = HelpCategory::create($categoryData);

            // Create articles for each category
            $articles = $this->getArticlesForCategory($category->slug);
            foreach ($articles as $articleData) {
                $articleData['help_category_id'] = $category->id;
                $articleData['slug'] = Str::slug($articleData['title']);
                HelpArticle::create($articleData);
            }
        }
    }

    private function getArticlesForCategory($categorySlug)
    {
        $articles = [
            'getting-started' => [
                [
                    'title' => 'Welcome to IntraGest',
                    'content' => "Welcome to IntraGest, your comprehensive boarding school management system. This guide will help you get started with the basic features and navigation.\n\nKey Features:\n- Dashboard overview\n- Student management\n- Staff management\n- Absence tracking\n- Payment processing\n- Stock management",
                    'excerpt' => 'Get started with IntraGest and learn about its key features',
                    'is_published' => true,
                ],
                [
                    'title' => 'Understanding Your Dashboard',
                    'content' => "The dashboard is your central hub for managing all aspects of your boarding school. Here's what you'll find:\n\n1. Quick Statistics\n2. Recent Activities\n3. Pending Approvals\n4. Important Notifications",
                    'excerpt' => 'Learn how to use your dashboard effectively',
                    'is_published' => true,
                ],
            ],
            'absence-management' => [
                [
                    'title' => 'Creating an Absence Request',
                    'content' => "Follow these steps to create an absence request:\n\n1. Navigate to Absence Management\n2. Click 'New Request'\n3. Fill in the required details\n4. Submit for approval",
                    'excerpt' => 'Step-by-step guide to creating absence requests',
                    'is_published' => true,
                ],
                [
                    'title' => 'Approving Absence Requests',
                    'content' => "For staff members with approval permissions:\n\n1. Review request details\n2. Check for conflicts\n3. Approve or reject with comments\n4. Monitor absence patterns",
                    'excerpt' => 'Learn how to manage and approve absence requests',
                    'is_published' => true,
                ],
            ],
            'payment-system' => [
                [
                    'title' => 'Processing Payments',
                    'content' => "Learn how to process payments efficiently:\n\n1. Access payment module\n2. Enter payment details\n3. Verify information\n4. Complete transaction\n5. Generate receipt",
                    'excerpt' => 'Guide to processing payments in the system',
                    'is_published' => true,
                ],
                [
                    'title' => 'Payment Reports and Analytics',
                    'content' => "Understanding payment reports:\n\n1. Accessing reports\n2. Filtering data\n3. Exporting results\n4. Analyzing trends",
                    'excerpt' => 'Learn about payment reporting features',
                    'is_published' => true,
                ],
            ],
            'stock-management' => [
                [
                    'title' => 'Managing Inventory Items',
                    'content' => "Effective inventory management:\n\n1. Adding new items\n2. Updating stock levels\n3. Setting reorder points\n4. Tracking stock movement",
                    'excerpt' => 'Learn how to manage inventory items',
                    'is_published' => true,
                ],
                [
                    'title' => 'Stock Reports and Alerts',
                    'content' => "Stay on top of your inventory:\n\n1. Low stock alerts\n2. Stock movement history\n3. Supplier management\n4. Stock valuation",
                    'excerpt' => 'Understanding stock reporting and alert system',
                    'is_published' => true,
                ],
            ],
        ];

        return $articles[$categorySlug] ?? [];
    }
}
