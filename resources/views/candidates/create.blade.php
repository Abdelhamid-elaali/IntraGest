@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Add New Candidate</h1>
        <p class="text-gray-600">Enter the details of the new candidate below.</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('candidates.store') }}" method="POST">
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
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Geographical Criteria -->
                <div>
                    <label for="distance" class="block text-sm font-medium text-gray-700 mb-1">Distance from Institution (km)</label>
                    <input type="number" name="distance" id="distance" value="{{ old('distance') }}" min="0" step="0.1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('distance')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Social Criteria -->
                <div>
                    <label for="income_level" class="block text-sm font-medium text-gray-700 mb-1">Income Level</label>
                    <select name="income_level" id="income_level" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="">Select Income Level</option>
                        <option value="low" {{ old('income_level') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('income_level') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('income_level') == 'high' ? 'selected' : '' }}>High</option>
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
                        <option value="beginner" {{ old('training_level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ old('training_level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="advanced" {{ old('training_level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
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
                        <option value="excellent" {{ old('physical_condition') == 'excellent' ? 'selected' : '' }}>Excellent</option>
                        <option value="good" {{ old('physical_condition') == 'good' ? 'selected' : '' }}>Good</option>
                        <option value="average" {{ old('physical_condition') == 'average' ? 'selected' : '' }}>Average</option>
                        <option value="poor" {{ old('physical_condition') == 'poor' ? 'selected' : '' }}>Poor</option>
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
                        <option value="single_parent" {{ old('family_status') == 'single_parent' ? 'selected' : '' }}>Single Parent</option>
                        <option value="both_parents" {{ old('family_status') == 'both_parents' ? 'selected' : '' }}>Both Parents</option>
                        <option value="orphan" {{ old('family_status') == 'orphan' ? 'selected' : '' }}>Orphan</option>
                        <option value="guardian" {{ old('family_status') == 'guardian' ? 'selected' : '' }}>Guardian</option>
                    </select>
                    @error('family_status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="siblings_count" class="block text-sm font-medium text-gray-700 mb-1">Number of Siblings</label>
                    <input type="number" name="siblings_count" id="siblings_count" value="{{ old('siblings_count', 0) }}" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('siblings_count')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ route('candidates.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Save Candidate
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
