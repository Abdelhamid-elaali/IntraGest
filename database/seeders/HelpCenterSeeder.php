<?php

namespace Database\Seeders;

use App\Models\HelpCategory;
use App\Models\HelpArticle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HelpCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Help Categories
        $categories = [
            [
                'name' => 'Getting Started',
                'slug' => 'getting-started',
                'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>',
                'description' => 'Learn the basics of using IntraGest',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Stock Management',
                'slug' => 'stock-management',
                'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>',
                'description' => 'Learn how to manage inventory and track stock levels',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Room Management',
                'slug' => 'room-management',
                'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>',
                'description' => 'Learn how to manage rooms and track occupancy',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Intern Management',
                'slug' => 'intern-management',
                'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>',
                'description' => 'Learn how to manage interns and track their progress',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Account Settings',
                'slug' => 'account-settings',
                'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>',
                'description' => 'Manage your account settings and preferences',
                'order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = HelpCategory::create($categoryData);
            
            // Create articles for each category
            $this->createArticlesForCategory($category);
        }
    }

    /**
     * Create articles for a specific category
     *
     * @param HelpCategory $category
     * @return void
     */
    private function createArticlesForCategory(HelpCategory $category)
    {
        $articles = [];
        
        switch ($category->slug) {
            case 'getting-started':
                $articles = [
                    [
                        'title' => 'Welcome to IntraGest',
                        'slug' => 'welcome-to-intragest',
                        'excerpt' => 'Learn about IntraGest and how it can help you manage your institution efficiently.',
                        'content' => '<p>Welcome to IntraGest, your all-in-one solution for managing educational institutions. This comprehensive platform is designed to streamline administrative tasks, enhance communication, and improve overall efficiency.</p><h3>Key Features</h3><ul><li>Student Management</li><li>Room Management</li><li>Stock Inventory</li><li>Staff Management</li><li>Reporting and Analytics</li></ul><p>This guide will help you get started with IntraGest and make the most of its features.</p>',
                        'view_count' => 120,
                        'is_published' => true,
                    ],
                    [
                        'title' => 'Navigating the Dashboard',
                        'slug' => 'navigating-the-dashboard',
                        'excerpt' => 'Learn how to navigate the IntraGest dashboard and access key features.',
                        'content' => '<p>The IntraGest dashboard is your central hub for accessing all features and monitoring key metrics. This guide will help you understand the layout and navigation.</p><h3>Dashboard Sections</h3><ul><li>Quick Stats: View important metrics at a glance</li><li>Navigation Menu: Access all modules</li><li>Recent Activity: See latest updates</li><li>Notifications: Stay informed about important events</li></ul><p>Use the sidebar navigation to access different modules such as Students, Rooms, Stock, and more.</p>',
                        'view_count' => 85,
                        'is_published' => true,
                    ],
                    [
                        'title' => 'Setting Up Your Profile',
                        'slug' => 'setting-up-your-profile',
                        'excerpt' => 'Learn how to set up and customize your user profile in IntraGest.',
                        'content' => '<p>Setting up your profile is an important first step when using IntraGest. This guide will walk you through the process.</p><h3>Profile Setup Steps</h3><ol><li>Click on your profile picture in the top-right corner</li><li>Select "Profile Settings" from the dropdown menu</li><li>Fill in your personal information</li><li>Upload a profile picture</li><li>Set your notification preferences</li><li>Save your changes</li></ol><p>A complete profile helps colleagues identify you and ensures you receive appropriate notifications.</p>',
                        'view_count' => 65,
                        'is_published' => true,
                    ],
                ];
                break;
                
            case 'stock-management':
                $articles = [
                    [
                        'title' => 'Introduction to Stock Management',
                        'slug' => 'introduction-to-stock-management',
                        'excerpt' => 'Learn the basics of managing inventory in IntraGest.',
                        'content' => '<p>The Stock Management module in IntraGest helps you track inventory, manage stock levels, and monitor usage patterns. This guide provides an overview of the key features.</p><h3>Key Features</h3><ul><li>Inventory Tracking: Monitor stock levels in real-time</li><li>Low Stock Alerts: Get notified when items are running low</li><li>Stock Analytics: View usage patterns and trends</li><li>Stock Transactions: Record stock additions and removals</li></ul><p>Effective stock management ensures you always have the supplies you need without overstocking.</p>',
                        'view_count' => 95,
                        'is_published' => true,
                    ],
                    [
                        'title' => 'Adding New Stock Items',
                        'slug' => 'adding-new-stock-items',
                        'excerpt' => 'Learn how to add new items to your inventory in IntraGest.',
                        'content' => '<p>Adding new stock items is a fundamental task in inventory management. This guide walks you through the process.</p><h3>Steps to Add New Stock</h3><ol><li>Navigate to the Stock Management module</li><li>Click "Add New Item" button</li><li>Fill in the item details (name, code, category, etc.)</li><li>Set the initial quantity and unit price</li><li>Define minimum and maximum quantities for alerts</li><li>Save the new item</li></ol><p>Properly categorizing and setting up stock items makes inventory management more efficient.</p>',
                        'view_count' => 75,
                        'is_published' => true,
                    ],
                    [
                        'title' => 'Understanding Stock Analytics',
                        'slug' => 'understanding-stock-analytics',
                        'excerpt' => 'Learn how to use the analytics features to optimize your inventory.',
                        'content' => '<p>Stock analytics provide valuable insights into your inventory usage patterns, helping you make informed decisions. This guide explains how to interpret and use these analytics.</p><h3>Key Analytics Features</h3><ul><li>Stock Value by Category: Visualize inventory value distribution</li><li>Stock Movement Trends: Track usage patterns over time</li><li>Top Moving Products: Identify frequently used items</li><li>Expiring Products: Monitor items approaching expiration</li></ul><p>Regular analysis of these metrics can help optimize ordering and reduce waste.</p>',
                        'view_count' => 60,
                        'is_published' => true,
                    ],
                ];
                break;
                
            case 'room-management':
                $articles = [
                    [
                        'title' => 'Room Management Overview',
                        'slug' => 'room-management-overview',
                        'excerpt' => 'Learn about the room management features in IntraGest.',
                        'content' => '<p>The Room Management module in IntraGest helps you track room availability, manage assignments, and monitor occupancy. This guide provides an overview of the key features.</p><h3>Key Features</h3><ul><li>Room Tracking: Monitor available and occupied rooms</li><li>Room Assignment: Assign rooms based on availability and needs</li><li>Occupancy Monitoring: Track room occupancy rates</li><li>Maintenance Tracking: Manage rooms under maintenance</li></ul><p>Effective room management ensures optimal use of your facility resources.</p>',
                        'view_count' => 88,
                        'is_published' => true,
                    ],
                    [
                        'title' => 'Adding and Configuring Rooms',
                        'slug' => 'adding-and-configuring-rooms',
                        'excerpt' => 'Learn how to add new rooms and configure their properties.',
                        'content' => '<p>Adding and properly configuring rooms is essential for effective room management. This guide walks you through the process.</p><h3>Steps to Add New Rooms</h3><ol><li>Navigate to the Room Management module</li><li>Click "Add New Room" button</li><li>Enter room details (number, floor, pavilion, etc.)</li><li>Set the room capacity and type</li><li>Define the initial status (available, unavailable, maintenance)</li><li>Save the new room</li></ol><p>Properly configuring rooms with accurate information ensures effective assignment and tracking.</p>',
                        'view_count' => 72,
                        'is_published' => true,
                    ],
                    [
                        'title' => 'Assigning Rooms to Interns',
                        'slug' => 'assigning-rooms-to-interns',
                        'excerpt' => 'Learn how to assign rooms to interns based on availability and needs.',
                        'content' => '<p>Assigning rooms to interns is a core function of the Room Management module. This guide explains the process and best practices.</p><h3>Room Assignment Process</h3><ol><li>Navigate to the Room Management module</li><li>Find an available room that meets the intern\'s needs</li><li>Click on the room to view details</li><li>Select "Assign Room" option</li><li>Choose the intern from the dropdown list</li><li>Set the assignment period (start and end dates)</li><li>Add any relevant notes</li><li>Confirm the assignment</li></ol><p>Matching interns with appropriate rooms improves satisfaction and resource utilization.</p>',
                        'view_count' => 65,
                        'is_published' => true,
                    ],
                ];
                break;
                
            case 'intern-management':
                $articles = [
                    [
                        'title' => 'Managing Intern Profiles',
                        'slug' => 'managing-intern-profiles',
                        'excerpt' => 'Learn how to create and manage intern profiles in IntraGest.',
                        'content' => '<p>Intern profiles contain essential information for effective management. This guide explains how to create and maintain these profiles.</p><h3>Intern Profile Management</h3><ol><li>Navigate to the Intern Management module</li><li>Click "Add New Intern" to create a profile</li><li>Enter personal details (name, ID, contact information)</li><li>Add academic information (program, start date, end date)</li><li>Upload required documents</li><li>Assign a room if needed</li><li>Save the profile</li></ol><p>Complete and up-to-date profiles facilitate better communication and management.</p>',
                        'view_count' => 82,
                        'is_published' => true,
                    ],
                    [
                        'title' => 'Tracking Intern Progress',
                        'slug' => 'tracking-intern-progress',
                        'excerpt' => 'Learn how to monitor and track intern progress and performance.',
                        'content' => '<p>Tracking intern progress is essential for ensuring successful internship experiences. This guide covers the tools available for monitoring performance.</p><h3>Progress Tracking Features</h3><ul><li>Attendance Monitoring: Track presence and absences</li><li>Task Completion: Monitor assigned tasks and projects</li><li>Evaluation Forms: Complete periodic assessments</li><li>Progress Reports: Generate comprehensive reports</li></ul><p>Regular progress tracking helps identify issues early and ensures interns meet their learning objectives.</p>',
                        'view_count' => 70,
                        'is_published' => true,
                    ],
                    [
                        'title' => 'Managing Intern Accommodations',
                        'slug' => 'managing-intern-accommodations',
                        'excerpt' => 'Learn how to manage housing and accommodations for interns.',
                        'content' => '<p>Managing accommodations is a critical aspect of intern management. This guide explains how to handle housing assignments and related issues.</p><h3>Accommodation Management Process</h3><ol><li>Assess intern housing needs and preferences</li><li>Check room availability in the Room Management module</li><li>Assign appropriate rooms based on gender, duration, and other factors</li><li>Process any accommodation fees</li><li>Handle room changes or maintenance issues</li><li>Manage check-out procedures at the end of internship</li></ol><p>Proper accommodation management contributes significantly to intern satisfaction.</p>',
                        'view_count' => 63,
                        'is_published' => true,
                    ],
                ];
                break;
                
            case 'account-settings':
                $articles = [
                    [
                        'title' => 'Managing Your Account',
                        'slug' => 'managing-your-account',
                        'excerpt' => 'Learn how to manage your account settings and preferences.',
                        'content' => '<p>Managing your account settings ensures a personalized and secure experience with IntraGest. This guide covers the essential account management tasks.</p><h3>Account Management Tasks</h3><ul><li>Updating Personal Information: Keep your details current</li><li>Changing Password: Maintain account security</li><li>Setting Notification Preferences: Control what alerts you receive</li><li>Managing Email Settings: Configure communication preferences</li><li>Two-Factor Authentication: Enhance account security</li></ul><p>Regularly reviewing and updating your account settings helps maintain security and improves your user experience.</p>',
                        'view_count' => 90,
                        'is_published' => true,
                    ],
                    [
                        'title' => 'Understanding User Roles and Permissions',
                        'slug' => 'understanding-user-roles-and-permissions',
                        'excerpt' => 'Learn about different user roles and what they can access in IntraGest.',
                        'content' => '<p>IntraGest uses a role-based access control system to manage what different users can see and do. This guide explains the various roles and their permissions.</p><h3>Common User Roles</h3><ul><li>Administrator: Full system access and configuration rights</li><li>Manager: Access to most features with some limitations</li><li>Staff: Access to day-to-day operational features</li><li>Intern: Limited access to personal information and resources</li><li>Guest: Minimal access to public information only</li></ul><p>Understanding your role and permissions helps you navigate the system effectively and maintain proper security protocols.</p>',
                        'view_count' => 75,
                        'is_published' => true,
                    ],
                    [
                        'title' => 'Customizing Your Dashboard',
                        'slug' => 'customizing-your-dashboard',
                        'excerpt' => 'Learn how to personalize your dashboard for better productivity.',
                        'content' => '<p>The IntraGest dashboard can be customized to show the information most relevant to your role and responsibilities. This guide explains how to personalize your view.</p><h3>Dashboard Customization Options</h3><ol><li>Click the "Customize" button in the top-right corner of the dashboard</li><li>Select which widgets to display</li><li>Arrange widgets by dragging and dropping</li><li>Configure each widget\'s settings</li><li>Set your preferred refresh rate for data</li><li>Save your custom layout</li></ol><p>A well-customized dashboard improves efficiency by putting the most important information at your fingertips.</p>',
                        'view_count' => 68,
                        'is_published' => true,
                    ],
                ];
                break;
        }
        
        foreach ($articles as $articleData) {
            $articleData['help_category_id'] = $category->id;
            HelpArticle::create($articleData);
        }
    }
}
