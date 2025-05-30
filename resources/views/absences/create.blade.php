@extends('layouts.app')

@section('title', 'Create Absence')

@section('content')
<div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Create New Absence
            </h2>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('absences.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                Back to List
            </a>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <form action="{{ route('absences.store') }}" method="POST" class="space-y-6 p-6">
            @csrf

            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Student Selection -->
                <div class="sm:col-span-3">
                    <label for="student_id" class="block text-sm font-medium text-gray-700">
                        Student
                    </label>
                    <div class="mt-1">
                        <select id="student_id" name="student_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">Select a student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Date -->
                <div class="sm:col-span-3">
                    <label for="date" class="block text-sm font-medium text-gray-700">
                        Date
                    </label>
                    <div class="mt-1">
                        <input type="date" name="date" id="date" value="{{ old('date') }}" required
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Type -->
                <div class="sm:col-span-3">
                    <label for="type" class="block text-sm font-medium text-gray-700">
                        Type
                    </label>
                    <div class="mt-1">
                        <select id="type" name="type" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">Select type</option>
                            <option value="excused" {{ old('type') == 'excused' ? 'selected' : '' }}>Excused</option>
                            <option value="unexcused" {{ old('type') == 'unexcused' ? 'selected' : '' }}>Unexcused</option>
                            <option value="late" {{ old('type') == 'late' ? 'selected' : '' }}>Late</option>
                        </select>
                        @error('type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Duration (for late type) -->
                <div class="sm:col-span-3" x-data="{ showDuration: false }" x-init="showDuration = '{{ old('type') }}' === 'late'">
                    <label for="duration" class="block text-sm font-medium text-gray-700">
                        Duration (minutes)
                    </label>
                    <div class="mt-1">
                        <input type="number" name="duration" id="duration" min="1" value="{{ old('duration') }}"
                            x-bind:required="showDuration"
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('duration')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Reason -->
                <div class="sm:col-span-6">
                    <label for="reason" class="block text-sm font-medium text-gray-700">
                        Reason
                    </label>
                    <div class="mt-1">
                        <textarea id="reason" name="reason" rows="3" required
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('reason') }}</textarea>
                        @error('reason')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="window.history.back()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </button>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Create Absence
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('type').addEventListener('change', function() {
        const durationField = document.getElementById('duration');
        if (this.value === 'late') {
            durationField.parentElement.parentElement.classList.remove('hidden');
            durationField.required = true;
        } else {
            durationField.parentElement.parentElement.classList.add('hidden');
            durationField.required = false;
        }
    });
</script>
@endpush
@endsection
