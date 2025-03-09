@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="space-y-6">
    <!-- General Settings -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Settings</h2>
            </div>

            @if (session('success'))
                <div class="mb-4 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 text-green-600 dark:text-green-200 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Theme Settings -->
                <div x-data="{ theme: $store.darkMode.on ? 'dark' : 'light' }">
                    <label class="text-base font-medium text-gray-900 dark:text-white">Theme</label>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Choose your preferred theme for the application.</p>
                    <div class="mt-4 space-y-4">
                        <div class="flex items-center">
                            <input 
                                id="theme-light" 
                                name="theme" 
                                type="radio" 
                                value="light" 
                                x-model="theme"
                                @change="$store.darkMode.on = false; window.dispatchEvent(new CustomEvent('dark-mode-changed', { detail: false }))"
                                class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500"
                            >
                            <label for="theme-light" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">Light</label>
                        </div>
                        <div class="flex items-center">
                            <input 
                                id="theme-dark" 
                                name="theme" 
                                type="radio" 
                                value="dark" 
                                x-model="theme"
                                @change="$store.darkMode.on = true; window.dispatchEvent(new CustomEvent('dark-mode-changed', { detail: true }))"
                                class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500"
                            >
                            <label for="theme-dark" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">Dark</label>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="pt-6">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="notifications_enabled" name="notifications_enabled" type="checkbox" value="1" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ auth()->user()->settings['notifications_enabled'] ?? true ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3">
                            <label for="notifications_enabled" class="text-base font-medium text-gray-900 dark:text-white">Enable Notifications</label>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Receive notifications about important updates and activities.</p>
                        </div>
                    </div>
                </div>

                <!-- Language Settings -->
                <div class="pt-6">
                    <label for="language" class="block text-base font-medium text-gray-900 dark:text-white">Language</label>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Select your preferred language for the application.</p>
                    <select id="language" name="language" class="mt-2 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 pl-3 pr-10 text-base text-gray-900 dark:text-white focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm">
                        <option value="en" {{ auth()->user()->settings['language'] ?? 'en' === 'en' ? 'selected' : '' }}>English</option>
                        <option value="fr" {{ auth()->user()->settings['language'] ?? '' === 'fr' ? 'selected' : '' }}>French</option>
                    </select>
                </div>

                <!-- Save Button -->
                <div class="pt-6">
                    <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
