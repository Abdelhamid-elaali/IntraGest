@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Manage Category Weights</h2>
                        <p class="mt-1 text-sm text-gray-500">Adjust the weights and scores for each category. Total weight must equal 100%.</p>
                    </div>
                    <a href="{{ route('criteria.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back to Criteria
                    </a>
                </div>
                
                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">There were {{ count($errors->all()) }} errors with your submission</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('criteria.updateScores') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Category</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Weight (%)
                                        <div class="text-xs font-normal text-gray-500">Must total 100%</div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($categories as $category)
                                    @php
                                        $scoreData = $scores->get($category);
                                        $weight = old("scores.{$category}.weight", $scoreData ? $scoreData->weight : 0);
                                        $score = old("scores.{$category}.score", $scoreData ? $scoreData->score : 0);
                                        $weightedScore = ($weight * $score) / 100;
                                        
                                        $categoryInfo = [
                                            'geographical' => ['name' => 'Geographical', 'icon' => 'location-marker', 'color' => 'blue', 'desc' => 'Location-based criteria'],
                                            'social' => ['name' => 'Social', 'icon' => 'user-group', 'color' => 'green', 'desc' => 'Social and community-related criteria'],
                                            'academic' => ['name' => 'Academic', 'icon' => 'academic-cap', 'color' => 'purple', 'desc' => 'Academic performance and achievements'],
                                            'physical' => ['name' => 'Physical', 'icon' => 'user', 'color' => 'yellow', 'desc' => 'Physical condition and health criteria'],
                                            'family' => ['name' => 'Family', 'icon' => 'home', 'color' => 'red', 'desc' => 'Family situation and background'],
                                        ][$category] ?? ['name' => ucfirst($category), 'icon' => 'question-mark-circle', 'color' => 'gray', 'desc' => ''];
                                    @endphp
                                    
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 flex-shrink-0 flex items-center justify-center rounded-full bg-{{ $categoryInfo['color'] }}-100">
                                                    @if($categoryInfo['icon'] === 'location-marker')
                                                        <svg class="h-6 w-6 text-{{ $categoryInfo['color'] }}-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        </svg>
                                                    @elseif($categoryInfo['icon'] === 'user-group')
                                                        <svg class="h-6 w-6 text-{{ $categoryInfo['color'] }}-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                        </svg>
                                                    @elseif($categoryInfo['icon'] === 'academic-cap')
                                                        <svg class="h-6 w-6 text-{{ $categoryInfo['color'] }}-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                                        </svg>
                                                    @elseif($categoryInfo['icon'] === 'user')
                                                        <svg class="h-6 w-6 text-{{ $categoryInfo['color'] }}-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                    @elseif($categoryInfo['icon'] === 'home')
                                                        <svg class="h-6 w-6 text-{{ $categoryInfo['color'] }}-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                                        </svg>
                                                    @else
                                                        <svg class="h-6 w-6 text-{{ $categoryInfo['color'] }}-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="font-medium text-gray-900">{{ $categoryInfo['name'] }}</div>
                                                    <div class="text-gray-500 text-xs">{{ $categoryInfo['desc'] }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4">
                                            <div class="relative rounded-md shadow-sm">
                                                <input type="number" 
                                                       name="scores[{{ $category }}][weight]" 
                                                       id="weight-{{ $category }}" 
                                                       value="{{ $weight }}" 
                                                       min="0" 
                                                       max="100" 
                                                       step="0.01" 
                                                       class="block w-full pr-10 sm:text-sm rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                                       oninput="updateTotalWeight()"
                                                       onblur="this.value = parseFloat(this.value || 0).toFixed(2)">
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">%</span>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                                <tr class="bg-gray-50">
                                    <td class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">Total</td>
                                    <td class="px-3 py-4 text-sm">
                                        <span id="total-weight" class="font-medium">{{ number_format(collect($scores)->sum('weight'), 1) }}%</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">Total Weight</span>
                            <span id="total-percentage" class="text-sm font-medium">{{ number_format(collect($scores)->sum('weight'), 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div id="total-progress" class="h-2.5 rounded-full transition-all duration-300" 
                                 style="width: {{ collect($scores)->sum('weight') }}%;
                                        {{ collect($scores)->sum('weight') == 100 ? 'background-color: #10B981;' : 'background-color: #F59E0B;' }}">
                            </div>
                        </div>
                        <p id="progress-message" class="mt-1 text-xs text-gray-500">
                            {{ collect($scores)->sum('weight') == 100 ? 'Perfect! Total is 100%' : 'Adjust weights to total exactly 100%' }}
                        </p>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Save Changes
                        </button>
                    </div>
                </form>
                
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-2">
                            <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">About Category Scores</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>
                                    The total of all scores should equal 100%. Adjust the scores to reflect the relative importance of each category in the overall evaluation.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Function to update the UI based on total weight
    function updateTotalWeight() {
        let totalWeight = 0;
        const categories = @json($categories);
        const progressBar = document.getElementById('total-progress');
        const totalPercentage = document.getElementById('total-percentage');
        const progressMessage = document.getElementById('progress-message');
        
        // Calculate total weight
        categories.forEach(category => {
            const weight = parseFloat(document.getElementById('weight-' + category).value) || 0;
            totalWeight += weight;
        });
        
        // Cap at 100% for display purposes
        const displayWeight = Math.min(totalWeight, 100);
        
        // Update progress bar and percentage
        progressBar.style.width = `${displayWeight}%`;
        totalPercentage.textContent = `${displayWeight.toFixed(1)}%`;
        
        // Update colors and messages based on total weight
        if (Math.abs(totalWeight - 100) < 0.1) {
            // Perfect 100%
            progressBar.style.backgroundColor = '#10B981'; // Green
            progressMessage.textContent = 'Perfect! Total is 100%';
            progressMessage.className = 'mt-1 text-xs text-green-600';
            totalPercentage.className = 'text-sm font-medium text-green-600';
        } else if (totalWeight > 100) {
            // Over 100%
            progressBar.style.backgroundColor = '#EF4444'; // Red
            progressMessage.textContent = `Total exceeds 100% by ${(totalWeight - 100).toFixed(1)}%`;
            progressMessage.className = 'mt-1 text-xs text-red-600';
            totalPercentage.className = 'text-sm font-medium text-red-600';
        } else {
            // Under 100%
            progressBar.style.backgroundColor = '#F59E0B'; // Yellow
            progressMessage.textContent = `Adjust weights to total exactly 100% (${(100 - totalWeight).toFixed(1)}% remaining)`;
            progressMessage.className = 'mt-1 text-xs text-yellow-600';
            totalPercentage.className = 'text-sm font-medium text-yellow-600';
        }
    }
    
    // Initialize the page
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const categories = @json($categories);
        
        // Add input event listeners to all weight inputs
        categories.forEach(category => {
            const input = document.getElementById('weight-' + category);
            
            // Update on input
            input.addEventListener('input', updateTotalWeight);
            
            // Format on blur
            input.addEventListener('blur', function() {
                let value = parseFloat(this.value) || 0;
                if (value < 0) value = 0;
                if (value > 100) value = 100;
                this.value = value.toFixed(2);
                updateTotalWeight();
            });
        });
        
        // Form submission validation
        form.addEventListener('submit', function(e) {
            let totalWeight = 0;
            
            categories.forEach(category => {
                const weight = parseFloat(document.getElementById('weight-' + category).value) || 0;
                totalWeight += weight;
            });
            
            if (Math.abs(totalWeight - 100) > 0.1) {
                e.preventDefault();
                alert('The total weight must equal exactly 100%. Current total: ' + totalWeight.toFixed(1) + '%');
                return false;
            }
            
            return true;
        });
        
        // Initialize the UI
        updateTotalWeight();
    });
    
    // Handle keyboard navigation between inputs
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const form = e.target.form;
            const index = Array.prototype.indexOf.call(form, e.target);
            
            // Find next input that's not disabled or hidden
            let nextIndex = index + 1;
            while (nextIndex < form.elements.length && 
                  (form.elements[nextIndex].type === 'hidden' || 
                   form.elements[nextIndex].disabled || 
                   form.elements[nextIndex].readOnly)) {
                nextIndex++;
            }
            
            if (nextIndex < form.elements.length) {
                form.elements[nextIndex].focus();
            }
        }
    });
</script>
@endpush
@endsection