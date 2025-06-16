@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">
                Edit Scores for {{ $candidate->first_name }} {{ $candidate->last_name }}
            </h2>
            <a href="{{ route('candidates.show', $candidate->id) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Back to Candidate
            </a>
        </div>

        <form action="{{ route('candidate-scores.update', $candidate->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            @foreach($criteriaByCategory as $category => $criteriaList)
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">
                        {{ ucfirst($category) }} Criteria
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($criteriaList as $criteria)
                            @php
                                $score = $candidate->criteria->firstWhere('id', $criteria->id)->pivot->score ?? null;
                            @endphp
                            <div class="space-y-2">
                                <label for="scores[{{ $criteria->id }}]" class="block text-sm font-medium text-gray-700">
                                    {{ $criteria->name }}
                                    @if($criteria->score)
                                        <span class="text-xs text-gray-500">(Score: {{ $criteria->score }}%)</span>
                                    @endif
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" 
                                           name="scores[{{ $criteria->id }}]" 
                                           id="scores_{{ $criteria->id }}" 
                                           value="{{ old('scores.' . $criteria->id, $score) }}" 
                                           min="0" 
                                           max="100" 
                                           step="0.01"
                                           class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm" id="price-currency">
                                            /100
                                        </span>
                                    </div>
                                </div>
                                @error('scores.' . $criteria->id)
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="flex justify-end mt-8">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Save Scores
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
