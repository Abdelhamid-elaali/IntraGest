@extends('layouts.app')

@section('title', 'Alert Testing Page')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Alert Testing Page</h1>
    <form action="{{ route('dashboard') }}" method="GET">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
            </button>
        </form>
    </div>
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <p class="text-gray-700 dark:text-gray-300 mb-4">
            Click the buttons below to trigger different types of alert messages.
            The alert should appear at the top of the page (usually below the main navigation bar).
        </p>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
            @php
                $alertTypes = ['info', 'success', 'warning', 'error', 'primary', 'secondary'];
                $buttonStyles = [
                    'info' => 'bg-blue-500 hover:bg-blue-700 text-white',
                    'success' => 'bg-green-500 hover:bg-green-700 text-white',
                    'warning' => 'bg-yellow-500 hover:bg-yellow-700 text-gray-800',
                    'error' => 'bg-red-500 hover:bg-red-700 text-white',
                    'primary' => 'bg-purple-500 hover:bg-purple-700 text-white',
                    'secondary' => 'bg-orange-500 hover:bg-orange-700 text-white',
                ];
            @endphp

            @foreach ($alertTypes as $type)
                <a href="{{ route('test.alert.show', ['alertType' => $type]) }}"
                   class="font-semibold py-2 px-4 rounded-md text-center transition duration-150 ease-in-out {{ $buttonStyles[$type] }}">
                    Show {{ ucfirst($type) }} Alert
                </a>
            @endforeach
        </div>

        <p class="text-gray-700 dark:text-gray-300 mt-4">
            The main application layout should automatically display any flashed session messages.
        </p>
        </div>
    </div>
</div>
@endsection
