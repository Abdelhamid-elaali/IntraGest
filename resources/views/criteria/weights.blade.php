@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Adjust Category Weights</h1>
        <p class="text-gray-600">Set the relative importance of each criteria category for candidate evaluation.</p>
    </div>

    @if(session('success'))
    <x-alert type="success" :auto-dismiss="true" :dismiss-after="4000" class="mb-4">
        {{ session('success') }}
    </x-alert>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('criteria.updateWeights') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="geographical" class="flex justify-between items-center mb-1">
                        <span class="text-md font-medium text-blue-700">Geographical Criteria</span>
                        <span class="text-gray-700 font-medium" id="geographical-value">{{ $categoryWeights['geographical'] }}%</span>
                    </label>
                    <input type="range" name="geographical" id="geographical" min="0" max="100" value="{{ $categoryWeights['geographical'] }}" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" oninput="document.getElementById('geographical-value').textContent = this.value + '%'">
                    <p class="text-xs text-gray-500 mt-1">Distance from institution, rural areas, etc.</p>
                    @error('geographical')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="social" class="flex justify-between items-center mb-1">
                        <span class="text-md font-medium text-green-700">Social Criteria</span>
                        <span class="text-gray-700 font-medium" id="social-value">{{ $categoryWeights['social'] }}%</span>
                    </label>
                    <input type="range" name="social" id="social" min="0" max="100" value="{{ $categoryWeights['social'] }}" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" oninput="document.getElementById('social-value').textContent = this.value + '%'">
                    <p class="text-xs text-gray-500 mt-1">Income level, social needs, etc.</p>
                    @error('social')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="academic" class="flex justify-between items-center mb-1">
                        <span class="text-md font-medium text-purple-700">Academic Criteria</span>
                        <span class="text-gray-700 font-medium" id="academic-value">{{ $categoryWeights['academic'] }}%</span>
                    </label>
                    <input type="range" name="academic" id="academic" min="0" max="100" value="{{ $categoryWeights['academic'] }}" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" oninput="document.getElementById('academic-value').textContent = this.value + '%'">
                    <p class="text-xs text-gray-500 mt-1">Training level, academic performance, etc.</p>
                    @error('academic')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="physical" class="flex justify-between items-center mb-1">
                        <span class="text-md font-medium text-yellow-700">Physical Criteria</span>
                        <span class="text-gray-700 font-medium" id="physical-value">{{ $categoryWeights['physical'] }}%</span>
                    </label>
                    <input type="range" name="physical" id="physical" min="0" max="100" value="{{ $categoryWeights['physical'] }}" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" oninput="document.getElementById('physical-value').textContent = this.value + '%'">
                    <p class="text-xs text-gray-500 mt-1">Disabilities, physical conditions, etc.</p>
                    @error('physical')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="family" class="flex justify-between items-center mb-1">
                        <span class="text-md font-medium text-red-700">Family Criteria</span>
                        <span class="text-gray-700 font-medium" id="family-value">{{ $categoryWeights['family'] }}%</span>
                    </label>
                    <input type="range" name="family" id="family" min="0" max="100" value="{{ $categoryWeights['family'] }}" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" oninput="document.getElementById('family-value').textContent = this.value + '%'">
                    <p class="text-xs text-gray-500 mt-1">Family structure, number of siblings, etc.</p>
                    @error('family')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <div class="flex items-center mb-4">
                        <div class="w-3 h-3 rounded-full bg-blue-600 mr-2"></div>
                        <span class="text-sm font-medium text-blue-700">Geographical:</span>
                        <span class="text-sm text-gray-700 ml-1" id="geographical-display">{{ $categoryWeights['geographical'] }}%</span>
                    </div>
                    <div class="flex items-center mb-4">
                        <div class="w-3 h-3 rounded-full bg-green-600 mr-2"></div>
                        <span class="text-sm font-medium text-green-700">Social:</span>
                        <span class="text-sm text-gray-700 ml-1" id="social-display">{{ $categoryWeights['social'] }}%</span>
                    </div>
                    <div class="flex items-center mb-4">
                        <div class="w-3 h-3 rounded-full bg-purple-600 mr-2"></div>
                        <span class="text-sm font-medium text-purple-700">Academic:</span>
                        <span class="text-sm text-gray-700 ml-1" id="academic-display">{{ $categoryWeights['academic'] }}%</span>
                    </div>
                    <div class="flex items-center mb-4">
                        <div class="w-3 h-3 rounded-full bg-yellow-600 mr-2"></div>
                        <span class="text-sm font-medium text-yellow-700">Physical:</span>
                        <span class="text-sm text-gray-700 ml-1" id="physical-display">{{ $categoryWeights['physical'] }}%</span>
                    </div>
                    <div class="flex items-center mb-4">
                        <div class="w-3 h-3 rounded-full bg-red-600 mr-2"></div>
                        <span class="text-sm font-medium text-red-700">Family:</span>
                        <span class="text-sm text-gray-700 ml-1" id="family-display">{{ $categoryWeights['family'] }}%</span>
                    </div>
                    <div class="flex items-center font-bold">
                        <span class="text-sm text-gray-900">Total:</span>
                        <span class="text-sm text-gray-900 ml-1" id="total-display">{{ $categoryWeights['geographical'] + $categoryWeights['social'] + $categoryWeights['academic'] + $categoryWeights['physical'] + $categoryWeights['family'] }}%</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ route('criteria.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-md mr-2">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md">
                    Save Weights
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sliders = ['geographical', 'social', 'academic', 'physical', 'family'];
        
        function updateTotal() {
            let total = 0;
            sliders.forEach(slider => {
                total += parseInt(document.getElementById(slider).value);
            });
            document.getElementById('total-display').textContent = total + '%';
        }
        
        sliders.forEach(slider => {
            const input = document.getElementById(slider);
            input.addEventListener('input', function() {
                document.getElementById(slider + '-display').textContent = this.value + '%';
                updateTotal();
            });
        });
    });
</script>
@endsection
