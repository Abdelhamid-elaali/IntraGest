@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Add New Trainee</h1>
        <p class="text-gray-600">Enter the details of the new trainee below.</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('students.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="academic_year" class="block text-sm font-medium text-gray-700 mb-1">Academic Year</label>
                    <select name="academic_year" id="academic_year" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="">Select Academic Year</option>
                        <option value="First Year" {{ old('academic_year') == 'First Year' ? 'selected' : '' }}>First Year</option>
                        <option value="Second Year" {{ old('academic_year') == 'Second Year' ? 'selected' : '' }}>Second Year</option>
                        <option value="Third Year" {{ old('academic_year') == 'Third Year' ? 'selected' : '' }}>Third Year</option>
                        <option value="Fourth Year" {{ old('academic_year') == 'Fourth Year' ? 'selected' : '' }}>Fourth Year</option>
                        <option value="Fifth Year" {{ old('academic_year') == 'Fifth Year' ? 'selected' : '' }}>Fifth Year</option>
                    </select>
                    @error('academic_year')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="specialization" class="block text-sm font-medium text-gray-700 mb-1">Specialization</label>
                    <input type="text" name="specialization" id="specialization" value="{{ old('specialization') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('specialization')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nationality" class="block text-sm font-medium text-gray-700 mb-1">Nationality</label>
                    <input type="text" name="nationality" id="nationality" value="{{ old('nationality') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('nationality')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="entry_date" class="block text-sm font-medium text-gray-700 mb-1">Entry Date</label>
                    <input type="date" name="entry_date" id="entry_date" value="{{ old('entry_date') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('entry_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <form action="{{ route('students.index')}}" method="GET">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>    
                    Cancel
                    </button>
                </form>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Trainee
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
