@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Add New Room</h1>
        <a href="{{ route('rooms.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            Back to List
        </a>
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

            <div class="mt-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Create Room
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
