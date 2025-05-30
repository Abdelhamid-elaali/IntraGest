@extends('layouts.app')

@section('content')
<div class="container">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Trainee Details</h1>
        <div class="flex space-x-2">
            <a href="{{ route('students.edit', $student) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Trainee
            </a>
            <a href="{{ route('students.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to List
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-3">Personal Information</h3>
                    
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Full Name</p>
                        <p class="text-base text-gray-900">{{ $student->name }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Email Address</p>
                        <p class="text-base text-gray-900">{{ $student->email }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Phone Number</p>
                        <p class="text-base text-gray-900">{{ $student->phone }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Address</p>
                        <p class="text-base text-gray-900">{{ $student->address }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Nationality</p>
                        <p class="text-base text-gray-900">{{ $student->nationality }}</p>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-3">Academic Information</h3>
                    
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Academic Year</p>
                        <p class="text-base text-gray-900">{{ $student->academic_year }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Specialization</p>
                        <p class="text-base text-gray-900">{{ $student->specialization }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Entry Date</p>
                        <p class="text-base text-gray-900">{{ $student->entry_date->format('d/m/Y') }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Trainee Since</p>
                        <p class="text-base text-gray-900">{{ $student->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
