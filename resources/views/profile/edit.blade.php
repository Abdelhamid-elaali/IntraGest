@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Edit Profile</h1>
            <a href="{{ route('profile.show') }}" class="text-blue-600 hover:text-blue-800">Back to Profile</a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" class="bg-white shadow-md rounded-lg p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Basic Information -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea name="address" id="address" rows="3"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">{{ old('address', $user->address) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Role Information -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Role Information</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="mt-4">
                            <h3 class="text-lg font-medium text-gray-900">Roles</h3>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($user->roles as $role)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Only show if user has specific roles -->
                        @if($user->isIntern())
                            <!-- Intern-specific info -->
                            <div class="mt-4">
                                <h3 class="text-lg font-medium text-gray-900">Internship Details</h3>
                                <!-- Add internship details here -->
                            </div>
                        @endif

                        @if($user->isCook())
                            <!-- Cook-specific info -->
                            <div class="mt-4">
                                <h3 class="text-lg font-medium text-gray-900">Kitchen Management</h3>
                                <!-- Add kitchen management details here -->
                            </div>
                        @endif

                        @if($user->isStockManager())
                            <!-- Stock Manager-specific info -->
                            <div class="mt-4">
                                <h3 class="text-lg font-medium text-gray-900">Stock Overview</h3>
                                <!-- Add stock management details here -->
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Update Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
