@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Add New Room</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('rooms.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Room Number -->
                <div>
                    <label for="room_number" class="block text-sm font-medium text-gray-700">Room N°</label>
                    <input type="text" name="room_number" id="room_number" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        value="{{ old('room_number') }}">
                </div>

                <!-- Floor -->
                <div>
                    <label for="floor" class="block text-sm font-medium text-gray-700">Étage</label>
                    <input type="number" name="floor" id="floor" required min="0"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        value="{{ old('floor') }}">
                </div>

                <!-- Pavilion -->
                <div>
                    <label for="pavilion" class="block text-sm font-medium text-gray-700">Pavillon</label>
                    <select name="pavilion" id="pavilion" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Pavilion</option>
                        <option value="Girls" {{ old('pavilion') == 'Girls' ? 'selected' : '' }}>Girls</option>
                        <option value="Boys" {{ old('pavilion') == 'Boys' ? 'selected' : '' }}>Boys</option>
                    </select>
                </div>

                <!-- Accommodation Type -->
                <div>
                    <label for="accommodation_type" class="block text-sm font-medium text-gray-700">Type d'hébergement</label>
                    <select name="accommodation_type" id="accommodation_type" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Type</option>
                        <option value="Personal" {{ old('accommodation_type') == 'Personal' ? 'selected' : '' }}>Personal</option>
                        <option value="Shared" {{ old('accommodation_type') == 'Shared' ? 'selected' : '' }}>Shared</option>
                    </select>
                </div>

                <!-- Capacity -->
                <div>
                    <label for="capacity" class="block text-sm font-medium text-gray-700">Nombre de stagiaires</label>
                    <input type="number" name="capacity" id="capacity" required min="1"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        value="{{ old('capacity') }}">
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                    <select name="status" id="status" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Status</option>
                        <option value="Available" {{ old('status') == 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="Unavailable" {{ old('status') == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                    </select>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <select name="description" id="description"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Description</option>
                        <option value="Occupied - Reservable" {{ old('description') == 'Occupied - Reservable' ? 'selected' : '' }}>Occupied - Reservable</option>
                        <option value="Vacant - Trainees" {{ old('description') == 'Vacant - Trainees' ? 'selected' : '' }}>Vacant - Trainees</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <a href="{{ route('rooms.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>    
                Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Create Room
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
