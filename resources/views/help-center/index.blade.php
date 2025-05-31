@extends('layouts.app')

@section('title', 'Help & Support')

@section('content')
<div class="bg-white shadow-sm rounded-lg">
    <div class="p-6 border-b border-gray-200">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">How can we help you?</h2>
            <p class="mt-4 text-lg text-gray-600">Search our help center or browse categories below</p>
            
            <!-- Search Form -->
            <form action="{{ route('help-center.search') }}" method="GET" class="mt-6 max-w-2xl mx-auto">
                <div class="flex">
                    <div class="relative flex-grow">
                        <input type="text" name="q" placeholder="Search for help..." class="block w-full rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pl-10" required>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-r-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Search
                    </button>
                </div>
            </form>
        </div>

        <!-- Popular Articles -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Popular Articles</h3>
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <a href="{{ route('help-center.show', 'inventory-management') }}" class="block p-4 rounded-lg border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all duration-200">
                    <h4 class="font-medium text-gray-900">Inventory Management Guide</h4>
                    <p class="mt-1 text-sm text-gray-500">Learn how to manage stock items, categories, and orders with the new Tailwind CSS interface.</p>
                </a>
                <a href="{{ route('help-center.show', 'supplier-management') }}" class="block p-4 rounded-lg border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all duration-200">
                    <h4 class="font-medium text-gray-900">Supplier Management</h4>
                    <p class="mt-1 text-sm text-gray-500">How to add, edit and manage suppliers with the modernized interface.</p>
                </a>
                <a href="{{ route('help-center.show', 'user-profiles') }}" class="block p-4 rounded-lg border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all duration-200">
                    <h4 class="font-medium text-gray-900">Managing Your Profile</h4>
                    <p class="mt-1 text-sm text-gray-500">Learn how to update your profile information and change your password.</p>
                </a>
            </div>
        </div>

        <!-- Help Categories -->
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Browse by Category</h3>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <!-- Inventory Management -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <span class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-blue-100 text-blue-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </span>
                        <h4 class="ml-3 text-lg font-medium text-gray-900">Inventory Management</h4>
                    </div>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('help-center.show', 'stock-items') }}" class="text-gray-600 hover:text-blue-600">
                                Managing Stock Items
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('help-center.show', 'stock-categories') }}" class="text-gray-600 hover:text-blue-600">
                                Working with Categories
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('help-center.show', 'stock-orders') }}" class="text-gray-600 hover:text-blue-600">
                                Creating and Managing Orders
                            </a>
                        </li>
                    </ul>
                    <a href="{{ route('help-center.category', 'inventory') }}" class="mt-4 inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                        View all articles
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                
                <!-- User Management -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <span class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-blue-100 text-blue-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </span>
                        <h4 class="ml-3 text-lg font-medium text-gray-900">User Management</h4>
                    </div>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('help-center.show', 'staff-management') }}" class="text-gray-600 hover:text-blue-600">
                                Managing Staff Members
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('help-center.show', 'user-profiles') }}" class="text-gray-600 hover:text-blue-600">
                                User Profiles
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('help-center.show', 'permissions') }}" class="text-gray-600 hover:text-blue-600">
                                Roles and Permissions
                            </a>
                        </li>
                    </ul>
                    <a href="{{ route('help-center.category', 'users') }}" class="mt-4 inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                        View all articles
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                
                <!-- General Usage -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <span class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-blue-100 text-blue-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </span>
                        <h4 class="ml-3 text-lg font-medium text-gray-900">General Usage</h4>
                    </div>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('help-center.show', 'ui-guide') }}" class="text-gray-600 hover:text-blue-600">
                                New UI Guide
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('help-center.show', 'getting-started') }}" class="text-gray-600 hover:text-blue-600">
                                Getting Started
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('help-center.show', 'faq') }}" class="text-gray-600 hover:text-blue-600">
                                Frequently Asked Questions
                            </a>
                        </li>
                    </ul>
                    <a href="{{ route('help-center.category', 'general') }}" class="mt-4 inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                        View all articles
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Contact Support -->
        <div class="mt-12 bg-gray-50 rounded-lg p-6">
            <div class="text-center">
                <h3 class="text-lg font-medium text-gray-900">Still need help?</h3>
                <p class="mt-2 text-sm text-gray-600">Contact our support team for personalized assistance</p>
                <a href="{{ route('help-center.contact') }}" class="mt-4 inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 py-2.5 px-6 text-sm font-medium text-white shadow-sm hover:bg-blue-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Contact Support
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
