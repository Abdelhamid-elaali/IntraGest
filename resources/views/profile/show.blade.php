@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Profile</h1>
            <a href="{{ route('profile.edit') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Edit Profile
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <!-- Basic Information -->
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Name</label>
                        <p class="mt-1 text-gray-900">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Email</label>
                        <p class="mt-1 text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Phone</label>
                        <p class="mt-1 text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Address</label>
                        <p class="mt-1 text-gray-900">{{ $user->address ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- Role Information -->
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Role & Permissions</h2>
                <div class="space-y-4">
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

            <!-- Academic Information (for students) -->
            @if($user->roles->contains('name', 'student'))
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Academic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Student ID</label>
                            <p class="mt-1 text-gray-900">{{ $user->student_id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Current Term</label>
                            <p class="mt-1 text-gray-900">
                                @if($currentTerm = \App\Models\AcademicTerm::where('is_current', true)->first())
                                    {{ $currentTerm->name }}
                                @else
                                    No active term
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Enrolled Subjects</label>
                            <p class="mt-1 text-gray-900">
                                {{ $user->enrollments()->where('status', 'approved')->count() }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Overall GPA</label>
                            <p class="mt-1 text-gray-900">
                                {{ number_format($user->grades()->avg('score'), 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Teaching Information (for teachers) -->
            <!-- Account Security -->
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Account Security</h2>
                <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                        <input type="password" name="current_password" id="current_password" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" name="password" id="password" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
