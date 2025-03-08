@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Room {{ $room->room_number }}</h1>
            <a href="{{ route('rooms.show', $room) }}" class="text-gray-600 hover:text-gray-900">
                Back to Room Details
            </a>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('rooms.update', $room) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Room Number -->
                    <div>
                        <label for="room_number" class="block text-sm font-medium text-gray-700">Room Number</label>
                        <input type="text" name="room_number" id="room_number" value="{{ old('room_number', $room->room_number) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('room_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Room Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Room Type</label>
                        <select name="type" id="type" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach(['single', 'double', 'triple', 'quad', 'suite'] as $type)
                                <option value="{{ $type }}" {{ old('type', $room->type) === $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Capacity -->
                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                        <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $room->capacity) }}" required min="1"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('capacity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price per Month -->
                    <div>
                        <label for="price_per_month" class="block text-sm font-medium text-gray-700">Price per Month</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="price_per_month" id="price_per_month" step="0.01" min="0"
                                value="{{ old('price_per_month', $room->price_per_month) }}" required
                                class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        @error('price_per_month')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach(['available', 'occupied', 'maintenance'] as $status)
                                <option value="{{ $status }}" {{ old('status', $room->status) === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Amenities -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Amenities</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @php
                                $amenities = [
                                    'air_conditioning' => 'Air Conditioning',
                                    'heating' => 'Heating',
                                    'private_bathroom' => 'Private Bathroom',
                                    'desk' => 'Study Desk',
                                    'wardrobe' => 'Wardrobe',
                                    'wifi' => 'Wi-Fi',
                                    'tv' => 'TV',
                                    'refrigerator' => 'Refrigerator',
                                    'microwave' => 'Microwave'
                                ];
                                $currentAmenities = old('amenities', $room->amenities ?? []);
                            @endphp

                            @foreach($amenities as $value => $label)
                                <div class="flex items-center">
                                    <input type="checkbox" name="amenities[]" id="amenity_{{ $value }}" value="{{ $value }}"
                                        {{ in_array($value, $currentAmenities) ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="amenity_{{ $value }}" class="ml-2 text-sm text-gray-700">{{ $label }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('amenities')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $room->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('rooms.show', $room) }}" 
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Update Room
                    </button>
                </div>
            </form>

            @can('delete', $room)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-medium text-red-600">Delete Room</h2>
                    <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded"
                            onclick="return confirm('Are you sure you want to delete this room? This action cannot be undone.')">
                            Delete Room
                        </button>
                    </form>
                </div>
                <p class="mt-2 text-sm text-gray-500">
                    Once you delete a room, it cannot be recovered. This will also remove all allocation history.
                    Only rooms without active allocations can be deleted.
                </p>
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection
