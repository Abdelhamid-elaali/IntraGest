<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'IntraGest') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Left side - Background Image and Content -->
        <div class="hidden lg:flex lg:w-3/5 relative overflow-hidden">
            <!-- Gradient Overlay -->
            <div class="absolute inset-0  from-primary-600/100 to-primary-100/90 z-10"></div>
            
            <!-- Background Image -->
            <img 
                src="{{ asset('images/ofppt.jpg') }}" 
                alt="OFPPT Building" 
                class="absolute inset-0 w-full h-full object-cover"
            >

            <!-- Content -->
            <div class="relative z-20 flex flex-col justify-between w-full p-12">
                <!-- Logo -->
                <div>
                    <a href="/" class="flex items-center space-x-3">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-14 w-14">
                        <span class="text-black text-3xl font-bold tracking-tight">IntraGest</span>
                    </a>
                </div>

                <!-- Welcome Text -->
                <div class="space-y-6 max-w-xl">

                </div>

                <!-- Features List -->
                <div class="space-y-4">
 
                </div>
            </div>
        </div>

        <!-- Right side - Login Form -->
        <div class="w-full lg:w-2/5 flex items-center justify-center p-6 sm:p-8 lg:p-12">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex items-center justify-center mb-8">
                    <a href="/" class="flex items-center space-x-2">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-10 w-10">
                        <span class="text-primary-600 text-xl font-bold">IntraGest</span>
                    </a>
                </div>

                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
