@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="space-y-6">
    <!-- General Settings -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Application Settings</h2>
                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>

            @if (session('success'))
                <x-alert type="success" :auto-dismiss="true" :dismiss-after="4000" class="mb-4">
                    {{ session('success') }}
                </x-alert>
            @endif
            
            @if (session('error'))
                <x-alert type="error" :auto-dismiss="true" :dismiss-after="4000" class="mb-4">
                    {{ session('error') }}
                </x-alert>
            @endif

            <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')



                <!-- Notification Settings -->
                <div class="p-6 bg-gray-50 rounded-lg border border-gray-100 mb-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-blue-100 rounded-md p-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-medium text-gray-900">Notification Preferences</h3>
                            <p class="text-sm text-gray-500 mb-3">Control how and when you receive notifications from the system.</p>
                            <div class="flex items-center">
                                <input id="notifications_enabled" name="notifications_enabled" type="checkbox" value="1" class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ (session('user_settings.notifications_enabled') ?? auth()->user()->settings['notifications_enabled'] ?? true) ? 'checked' : '' }}>
                                <label for="notifications_enabled" class="ml-3 block text-sm font-medium text-gray-700">Enable all notifications</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Language Settings -->
                <div class="p-6 bg-gray-50 rounded-lg border border-gray-100">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-green-100 rounded-md p-2">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-medium text-gray-900">Language Preferences</h3>
                            <p class="text-sm text-gray-500 mb-3">Select your preferred language for the application interface.</p>
                            <select id="language" name="language" class="block w-full rounded-md border-gray-300 bg-white py-2 pl-3 pr-10 text-base text-gray-900 focus:border-green-500 focus:outline-none focus:ring-green-500 sm:text-sm">
                                <option value="en" {{ (session('user_settings.language') ?? auth()->user()->settings['language'] ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                                <option value="fr" {{ (session('user_settings.language') ?? auth()->user()->settings['language'] ?? '') == 'fr' ? 'selected' : '' }}>French</option>
                                <option value="ar" {{ (session('user_settings.language') ?? auth()->user()->settings['language'] ?? '') == 'ar' ? 'selected' : '' }}>Arabic (العربية)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="pt-6 flex justify-end">
                    <button type="submit" class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 py-2.5 px-6 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Preferences
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
