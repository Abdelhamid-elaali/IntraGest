<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'IntraGest') }} - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        function initSidebar() {
            return {
                isOpen: localStorage.getItem('sidebarState') === 'true',
                toggleSidebar() {
                    this.isOpen = !this.isOpen;
                    localStorage.setItem('sidebarState', this.isOpen);
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased h-full transition-colors duration-200 bg-gray-50">
    <div class="min-h-full">
        <!-- Top Navigation Bar -->
        <nav class="bg-gray-100 shadow-sm fixed w-full z-20">
            <div class="w-full mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-10 w-10">
                                <span class="text-black text-xl font-bold">IntraGest</span>
                            </a>
                        </div>
                    </div>

                    <!-- Center Search Bar -->
                    <div class="flex-1 flex items-center justify-center px-6 lg:px-8" x-data="search">
                        <div class="w-full max-w-lg lg:max-w-xl relative">
                            <label for="search" class="sr-only">Search</label>
                            <div class="relative text-black">
                                <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input
                                    id="search"
                                    name="search"
                                    class="block w-full border-2 border-black dark:border-blue-700 bg-gray-200 dark:bg-gray-300 border-transparent rounded-lg py-2 pl-10 pr-3 text-sm placeholder-black font-semibold text-white focus:outline-none focus:bg-white focus:text-gray-900 focus:placeholder-gray-400 focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-600 focus:ring-white sm:text-sm transition-colors duration-200"
                                    placeholder="Search sections (Press '/' to focus)"
                                    type="search"
                                    x-model="query"
                                    @focus="showResults = true"
                                    @click.away="showResults = false"
                                    @keydown.escape="showResults = false"
                                    @keydown.window="
                                        if (event.key === '/' && document.activeElement !== $el) {
                                            event.preventDefault();
                                            $el.focus();
                                        }
                                    "
                                >
                            </div>

                            <!-- Search Results Dropdown -->
                            <div
                                x-show="showResults && query.length > 0"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-1"
                                class="absolute z-50 mt-2 w-full bg-white dark:bg-gray-800 rounded-md shadow-lg max-h-96 overflow-y-auto"
                                style="display: none;"
                            >
                                <ul class="py-2">
                                    <template x-for="section in filteredSections" :key="section.route">
                                        <li>
                                            <a :href="section.route"
                                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                                               @click="showResults = false">
                                                <span x-html="getIcon(section.icon)" class="mr-3"></span>
                                                <span x-text="section.name"></span>
                                            </a>
                                        </li>
                                    </template>
                                    <li x-show="filteredSections.length === 0" class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                        No results found
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side Navigation -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications Dropdown -->
                        <div x-data="{ open: false }" @click.away="open = false" class="relative">
                            <button @click="open = !open" class="p-2 rounded-[20px] bg-gray-200 dark:bg-gray-700 hover:bg-blue-400 dark:hover:bg-gray-600 transition-colors duration-200">
                                <span class="sr-only">View notifications</span>
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <!-- Notification Badge -->
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white"></span>
                                @endif
                            </button>

                            <!-- Notifications Panel -->
                            <div x-show="open"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-80 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 dark:bg-gray-800"
                                role="menu"
                                style="display: none;">
                                <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-200">Notifications</h3>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ $notification->read_at ? 'opacity-75' : '' }}">
                                            <p class="font-medium">{{ $notification->data['title'] ?? 'Notification' }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                                        </a>
                                    @empty
                                        <div class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                            No notifications
                                        </div>
                                    @endforelse
                                </div>
                                @if(auth()->user()->notifications->count() > 5)
                                    <a href="{{ route('notifications.index') }}" class="block px-4 py-2 text-xs text-center text-blue-600 hover:text-blue-800 border-t border-gray-100 dark:border-gray-700">
                                        View all notifications
                                    </a>
                                @endif
                            </div>
                        </div>


                        <!-- Profile Dropdown -->
                        <div x-data="{ open: false }" @click.away="open = false" class="relative">
                            <button @click="open = !open" class="flex items-center space-x-3 text-black hover:text-blue-400 focus:outline-none">
                                <div class="flex items-center space-x-3">
                                    <img class="h-10 w-10 p-1 rounded-[25px] bg-gray-200 dark:bg-gray-700 hover:bg-blue-400 dark:hover:bg-gray-600 transition-colors duration-200 object-cover" src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" alt="{{ Auth::user()->name }}">
                                    <div class="text-sm">
                                        <div :class="isOpen ? 'opacity-100' : 'opacity-0'" class="transition-opacity duration-300">
                                            <span class="block text-sm font-medium text-black">{{ Auth::user()->name }}</span>
                                            <span class="block text-xs text-blue-500">{{ Auth::user()->primaryRole()?->name ?? 'User' }}</span>
                                        </div>
                                    </div>
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </button>

                            <!-- Profile Dropdown Panel -->
                            <div x-show="open"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 dark:bg-gray-800"
                                role="menu"
                                style="display: none;">
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700" role="menuitem">
                                    <span :class="isOpen ? 'opacity-100' : 'opacity-0 hidden'" class="transition-opacity duration-300">Profile</span> Settings
                                </a>
                                <a href="{{ route('profile.notifications') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700" role="menuitem">
                                    Notifications
                                </a>
                                <div class="border-t border-gray-100 dark:border-gray-700"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700" role="menuitem">
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </nav>

<!-- Left Sidebar -->
<div class="flex">
    <!-- Sidebar -->
    <div
        x-data="initSidebar()"
        class="fixed left-0 top-12 bottom-6 bg-white border-r border-gray-200 transition-all duration-300 ease-in-out z-10 overflow-y-auto shadow-sm h-full"
        :class="isOpen ? 'w-56' : 'w-14'"
    >
        <!-- Toggle Button Section -->
        <div class="pt-16"></div>
        <button 
            @click="toggleSidebar()"
            class="w-full h-12 flex items-center justify-center text-blue-500 hover:bg-blue-50 focus:outline-none focus:bg-blue-50 transition-all duration-200 group sticky top-0 bg-white"
        >
            <div class="flex items-center justify-center">
                <svg 
                    :class="isOpen ? 'rotate-0' : 'rotate-180'"
                    class="w-5 h-5 transition-transform duration-300" 
                    fill="none" 
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </button>
        <nav class="py-1 px-1 space-y-1 relative">
            <a href="{{ route('dashboard') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md overflow-hidden whitespace-nowrap {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-6 h-6 mr-3 {{ request()->routeIs('dashboard') ? 'text-blue-500' : 'text-gray-400' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span :class="isOpen ? 'opacity-100' : 'opacity-0 hidden'" class="transition-opacity duration-300">Dashboard</span>
            </a>
            <a href="{{ route('payments.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md overflow-hidden whitespace-nowrap {{ request()->routeIs('payments.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-gray-100' }}">
                <svg class="w-6 h-6 mr-3 {{ request()->routeIs('payments.*') ? 'text-blue-500' : 'text-gray-400 dark:text-gray-500' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span :class="isOpen ? 'opacity-100' : 'opacity-0 hidden'" class="transition-opacity duration-300">Payment Management</span>
            </a>

            <a href="{{ route('rooms.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md overflow-hidden whitespace-nowrap {{ request()->routeIs('rooms.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-gray-100' }}">
                <svg class="w-6 h-6 mr-3 {{ request()->routeIs('rooms.index') ? 'text-blue-500' : 'text-gray-400 dark:text-gray-500' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <span :class="isOpen ? 'opacity-100' : 'opacity-0 hidden'" class="transition-opacity duration-300">Room Management</span>
            </a>

            <a href="{{ route('stocks.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md overflow-hidden whitespace-nowrap {{ request()->routeIs('stocks.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-gray-100' }}">
                <svg class="w-6 h-6 mr-3 {{ request()->routeIs('stocks.*') ? 'text-blue-500' : 'text-gray-400 dark:text-gray-500' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <span :class="isOpen ? 'opacity-100' : 'opacity-0 hidden'" class="transition-opacity duration-300">Stock Management</span>
            </a>

            <!-- Trainee Management Section -->
            <div x-data="{open: {{ request()->routeIs('students.*') || request()->routeIs('candidates.*') || request()->routeIs('criteria.*') ? 'true' : 'false' }} }" class="mt-1">
                <button @click="open = !open" class="w-full flex items-center px-2 py-2 text-sm font-medium rounded-md overflow-hidden whitespace-nowrap text-gray-700 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-gray-100">
                    <svg class="w-6 h-6 mr-3 {{ request()->routeIs('students.*') || request()->routeIs('candidates.*') || request()->routeIs('criteria.*') ? 'text-blue-500' : 'text-gray-400 dark:text-gray-500' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                    </svg>
                    <span :class="isOpen ? 'opacity-100' : 'opacity-0 hidden'" class="transition-opacity duration-300 font-medium">Trainee Management</span>
                    <svg :class="isOpen ? 'opacity-100 ml-auto transform transition-transform duration-200' : 'opacity-0'" :class="open ? 'rotate-90' : ''" class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                
                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="pl-6 mt-1 space-y-1">
                    <a href="{{ route('students.index') }}" class="flex items-center px-2 py-1.5 text-xs font-medium rounded-md overflow-hidden whitespace-nowrap {{ request()->routeIs('students.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('students.index') ? 'text-blue-500' : 'text-gray-400' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span :class="isOpen ? 'opacity-100 ml-2' : 'opacity-0 hidden'" class="transition-opacity duration-300">Trainee List</span>
                    </a>
                    <a href="{{ route('candidates.index') }}" class="flex items-center px-2 py-1.5 text-xs font-medium rounded-md overflow-hidden whitespace-nowrap {{ request()->routeIs('candidates.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('candidates.index') ? 'text-blue-500' : 'text-gray-400' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span :class="isOpen ? 'opacity-100 ml-2' : 'opacity-0 hidden'" class="transition-opacity duration-300">Candidates</span>
                    </a>
                    <a href="{{ route('candidates.accepted') }}" class="flex items-center px-2 py-1.5 text-xs font-medium rounded-md overflow-hidden whitespace-nowrap {{ request()->routeIs('candidates.accepted') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('candidates.accepted') ? 'text-blue-500' : 'text-gray-400' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span :class="isOpen ? 'opacity-100 ml-2' : 'opacity-0 hidden'" class="transition-opacity duration-300">Accepted Candidates</span>
                    </a>
                    <a href="{{ route('criteria.index') }}" class="flex items-center px-2 py-1.5 text-xs font-medium rounded-md overflow-hidden whitespace-nowrap {{ request()->routeIs('criteria.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('criteria.index') ? 'text-blue-500' : 'text-gray-400' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        <span :class="isOpen ? 'opacity-100 ml-2' : 'opacity-0 hidden'" class="transition-opacity duration-300">Acceptance Criteria</span>
                    </a>
                </div>
            </div>

            <a href="{{ route('help-center.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md overflow-hidden whitespace-nowrap {{ request()->routeIs('help-center.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-gray-100' }}">
                <svg class="w-6 h-6 mr-3 {{ request()->routeIs('help-center.*') ? 'text-blue-500' : 'text-gray-400 dark:text-gray-500' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span :class="isOpen ? 'opacity-100' : 'opacity-0 hidden'" class="transition-opacity duration-300">Help & Support</span>
            </a>

            <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('profile.show') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md overflow-hidden whitespace-nowrap {{ request()->routeIs('profile.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-gray-100' }}">
                    <svg class="w-6 h-6 mr-3 {{ request()->routeIs('profile.*') ? 'text-blue-500' : 'text-gray-400 dark:text-gray-500' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span :class="isOpen ? 'opacity-100' : 'opacity-0 hidden'" class="transition-opacity duration-300">Profile</span>
                </a>

                @if(auth()->user()->isDirector() || auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                <a href="{{ route('staff.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md overflow-hidden whitespace-nowrap {{ request()->routeIs('staff.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-gray-100' }}">
                    <svg class="w-6 h-6 mr-3 {{ request()->routeIs('staff.*') ? 'text-blue-500' : 'text-gray-400 dark:text-gray-500' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span :class="isOpen ? 'opacity-100' : 'opacity-0 hidden'" class="transition-opacity duration-300">Staff Management</span>
                </a>
                @endif

                <a href="{{ route('settings.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md overflow-hidden whitespace-nowrap {{ request()->routeIs('settings.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-gray-100' }}">
                    <svg class="w-6 h-6 mr-3 {{ request()->routeIs('settings.*') ? 'text-blue-500' : 'text-gray-400 dark:text-gray-500' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.9924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span :class="isOpen ? 'opacity-100' : 'opacity-0 hidden'" class="transition-opacity duration-300">Settings</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-2 py-2 text-sm font-medium rounded-md text-red-600 hover:bg-red-50 hover:text-red-900 dark:text-red-400 dark:hover:bg-red-600 dark:hover:text-red-100">
                        <svg class="w-6 h-6 mr-3 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v6"></path>
                        </svg>
                        <span :class="isOpen ? 'opacity-100' : 'opacity-0 hidden'" class="transition-opacity duration-300">Logout</span>
                    </button>
                </form>
            </div>
        </nav>
    </div>

</div>

<!-- Main Content -->
<div class="min-h-screen bg-gray-50 pt-16" x-data="initSidebar()">
    <div class="transition-all duration-300 ease-in-out" :class="isOpen ? 'ml-60' : 'ml-20'">
        <div class="p-6" style="padding-left: 80px;">
            <div class="bg-white rounded-lg shadow p-12 overflow-hidden">
                @yield('content')
            </div>
        </div>
    </div>
</div>

@stack('modals')
@stack('scripts')
</body>
</html>
