@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Edit Candidate</h1>
        <p class="text-gray-600">Update the candidate's information below.</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('candidates.update', $candidate) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $candidate->name) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $candidate->email) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $candidate->phone) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <input type="text" name="address" id="address" value="{{ old('address', $candidate->address) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Geographical Criteria -->
                <div>
                    <label for="distance" class="block text-sm font-medium text-gray-700 mb-1">Distance from Institution (km)</label>
                    <input type="number" name="distance" id="distance" value="{{ old('distance', $candidate->distance) }}" min="0" step="0.1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('distance')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Social Criteria -->
                <div>
                    <label for="income_level" class="block text-sm font-medium text-gray-700 mb-1">Income Level</label>
                    <select name="income_level" id="income_level" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="">Select Income Level</option>
                        <option value="low" {{ old('income_level', $candidate->income_level) == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('income_level', $candidate->income_level) == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('income_level', $candidate->income_level) == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                    @error('income_level')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Academic Criteria -->
                <div>
                    <label for="training_level" class="block text-sm font-medium text-gray-700 mb-1">Training Level</label>
                    <select name="training_level" id="training_level" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="">Select Training Level</option>
                        <option value="beginner" {{ old('training_level', $candidate->training_level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ old('training_level', $candidate->training_level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="advanced" {{ old('training_level', $candidate->training_level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                    @error('training_level')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Physical Criteria -->
                <div>
                    <label for="physical_condition" class="block text-sm font-medium text-gray-700 mb-1">Physical Condition</label>
                    <select name="physical_condition" id="physical_condition" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="">Select Physical Condition</option>
                        <option value="excellent" {{ old('physical_condition', $candidate->physical_condition) == 'excellent' ? 'selected' : '' }}>Excellent</option>
                        <option value="good" {{ old('physical_condition', $candidate->physical_condition) == 'good' ? 'selected' : '' }}>Good</option>
                        <option value="average" {{ old('physical_condition', $candidate->physical_condition) == 'average' ? 'selected' : '' }}>Average</option>
                        <option value="poor" {{ old('physical_condition', $candidate->physical_condition) == 'poor' ? 'selected' : '' }}>Poor</option>
                    </select>
                    @error('physical_condition')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Family Criteria -->
                <div>
                    <label for="family_status" class="block text-sm font-medium text-gray-700 mb-1">Family Status</label>
                    <select name="family_status" id="family_status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="">Select Family Status</option>
                        <option value="single_parent" {{ old('family_status', $candidate->family_status) == 'single_parent' ? 'selected' : '' }}>Single Parent</option>
                        <option value="both_parents" {{ old('family_status', $candidate->family_status) == 'both_parents' ? 'selected' : '' }}>Both Parents</option>
                        <option value="orphan" {{ old('family_status', $candidate->family_status) == 'orphan' ? 'selected' : '' }}>Orphan</option>
                        <option value="guardian" {{ old('family_status', $candidate->family_status) == 'guardian' ? 'selected' : '' }}>Guardian</option>
                    </select>
                    @error('family_status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="siblings_count" class="block text-sm font-medium text-gray-700 mb-1">Number of Siblings</label>
                    <input type="number" name="siblings_count" id="siblings_count" value="{{ old('siblings_count', $candidate->siblings_count) }}" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('siblings_count')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ route('candidates.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-md mr-2">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md">
                    Update Candidate
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
