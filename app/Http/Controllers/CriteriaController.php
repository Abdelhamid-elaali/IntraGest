<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\CategoryScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CriteriaController extends Controller
{
    /**
     * Display a listing of the criteria.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Get criteria by category for API
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Get criteria by category for API
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByCategory(Request $request)
    {
        try {
            $validated = $request->validate([
                'category' => 'required|string|in:geographical,social,academic,physical,family'
            ]);
            
            $criteria = Criteria::byCategory($validated['category'])
                ->orderBy('name')
                ->select(['id', 'name as text', 'score', 'description'])
                ->get();
                
            return response()->json([
                'success' => true,
                'data' => $criteria
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching criteria by category: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching criteria.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    public function index()
    {
        // Get the authenticated user
        $user = auth()->user();
        
        // Get the candidate associated with the user (if any)
        $candidate = null;
        if ($user && $user->candidate) {
            $candidate = $user->candidate;
        }
        
        // Fetch criteria from database and group by category
        $geographicalCriteria = Criteria::where('category', 'geographical')->get();
        $socialCriteria = Criteria::where('category', 'social')->get();
        $academicCriteria = Criteria::where('category', 'academic')->get();
        $physicalCriteria = Criteria::where('category', 'physical')->get();
        $familyCriteria = Criteria::where('category', 'family')->get();
        
        // If we have a candidate, load their criteria scores
        if ($candidate) {
            $candidateCriteria = $candidate->criteria->pluck('pivot.score', 'id')->toArray();
            
            // Add scores to each criterion
            $geographicalCriteria->each(function($criterion) use ($candidateCriteria) {
                $criterion->candidate_score = $candidateCriteria[$criterion->id] ?? null;
            });
            
            $socialCriteria->each(function($criterion) use ($candidateCriteria) {
                $criterion->candidate_score = $candidateCriteria[$criterion->id] ?? null;
            });
            
            $academicCriteria->each(function($criterion) use ($candidateCriteria) {
                $criterion->candidate_score = $candidateCriteria[$criterion->id] ?? null;
            });
            
            $physicalCriteria->each(function($criterion) use ($candidateCriteria) {
                $criterion->candidate_score = $candidateCriteria[$criterion->id] ?? null;
            });
            
            $familyCriteria->each(function($criterion) use ($candidateCriteria) {
                $criterion->candidate_score = $candidateCriteria[$criterion->id] ?? null;
            });
        }
        
        // Get category scores from the database
        $categoryScores = CategoryScore::getAllScores();
        
        // Define default categories
        $categories = ['geographical', 'social', 'academic', 'physical', 'family'];
        
        // If no scores exist, create default ones
        if ($categoryScores->isEmpty()) {
            $defaultWeights = [
                'geographical' => 20,
                'social' => 20,
                'academic' => 20,
                'physical' => 20,
                'family' => 20
            ];
            
            // Save default scores to the database
            foreach ($defaultWeights as $category => $weight) {
                CategoryScore::create([
                    'category' => $category,
                    'weight' => $weight,
                    'score' => 0
                ]);
            }
            
            // Refresh the scores
            $categoryScores = CategoryScore::getAllScores();
        }
        
        // Convert to array format for the view
        $categoryScoresArray = [];
        foreach ($categoryScores as $category => $score) {
            $categoryScoresArray[$category] = $score->weight ?? 0;
        }
        
        return view('criteria.index', [
            'geographicalCriteria' => $geographicalCriteria,
            'socialCriteria' => $socialCriteria,
            'academicCriteria' => $academicCriteria,
            'physicalCriteria' => $physicalCriteria,
            'familyCriteria' => $familyCriteria,
            'categoryScores' => $categoryScoresArray,
            'categoryScoresData' => $categoryScores // Pass the full collection to the view
        ]);
    }

    /**
     * Show the form for creating a new criteria.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('criteria.create');
    }
    
    /**
     * Display the form to adjust category weights.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Display the form to adjust category weights and scores.
     *
     * @return \Illuminate\Http\Response
     */
    public function scores()
    {
        // Get the current scores from the database
        $categoryScores = CategoryScore::getAllScores();
        
        // Define default categories
        $categories = ['geographical', 'social', 'academic', 'physical', 'family'];
        
        // If no scores exist, create default ones
        if ($categoryScores->isEmpty()) {
            $defaultWeights = [
                'geographical' => 20,
                'social' => 20,
                'academic' => 20,
                'physical' => 20,
                'family' => 20
            ];
            
            // Save default scores to the database
            foreach ($defaultWeights as $category => $weight) {
                CategoryScore::create([
                    'category' => $category,
                    'weight' => $weight,
                    'score' => 0
                ]);
            }
            
            // Refresh the scores
            $categoryScores = CategoryScore::getAllScores();
        }
        
        return view('criteria.scores', [
            'scores' => $categoryScores,
            'categories' => $categories
        ]);
    }
    
    /**
     * Update the category weights and scores.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateScores(Request $request)
    {
        $validated = $request->validate([
            'scores' => 'required|array',
            'scores.*.weight' => 'required|numeric|min:0|max:100',
        ]);

        // Calculate total weight
        $totalWeight = collect($validated['scores'])->sum(function($item) {
            return $item['weight'];
        });

        // Allow for floating point imprecision
        if (abs($totalWeight - 100) > 0.01) {
            return back()->withErrors(['scores' => 'The sum of all weights must be exactly 100%. Current total: ' . round($totalWeight, 2) . '%']);
        }

        // Update the weights in the database
        foreach ($validated['scores'] as $category => $data) {
            CategoryScore::updateOrCreate(
                ['category' => $category],
                [
                    'weight' => $data['weight'],
                    'score' => 0 // Keep score as 0 since we're not using it
                ]
            );
        }

        return redirect()->route('criteria.index')
            ->with('success', 'Category weights updated successfully!');
    }

    /**
     * Store a newly created criteria in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'criteria' => 'required|array|min:1',
            'criteria.*.name' => 'required|string|max:255',
            'criteria.*.category' => 'required|string|in:geographical,social,academic,physical,family',
            'criteria.*.score' => 'required|integer|min:1|max:100',
            'criteria.*.description' => 'nullable|string',
        ]);
        
        // Calculate total score
        $totalScore = collect($validated['criteria'])->sum('score');
        if ($totalScore > 100) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'The total score points cannot exceed 100. Current total: ' . $totalScore);
        }
        
        try {
            DB::beginTransaction();
            
            $created = [];
            
            foreach ($validated['criteria'] as $criterion) {
                $criteria = Criteria::create([
                    'name' => $criterion['name'],
                    'category' => $criterion['category'],
                    'score' => $criterion['score'],
                    'description' => $criterion['description'] ?? null,
                ]);
                
                $created[] = $criteria;
            }
            
            DB::commit();
            
            if (count($created) === 1) {
                return redirect()->route('criteria.index')
                    ->with('success', 'Criterion created successfully!');
            } else {
                return redirect()->route('criteria.index')
                    ->with('success', count($created) . ' criteria created successfully!');
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating criteria: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'An error occurred while creating the criteria. Please try again.');
        }
    }

    /**
     * Display the specified criteria.
     *
     * @param  \App\Models\Criteria  $criteria
     * @return \Illuminate\Http\Response
     */
    public function show(Criteria $criteria)
    {
        return view('criteria.show', compact('criteria'));
    }

    /**
     * Show the form for editing the specified criteria.
     *
     * @param  \App\Models\Criteria  $criteria
     * @return \Illuminate\Http\Response
     */
    public function edit($criterion)
    {
        $criteria = Criteria::findOrFail($criterion);
        return view('criteria.edit', compact('criteria'));
    }

    /**
     * Update the specified criteria in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Criteria  $criteria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $criterion)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:geographical,social,academic,physical,family',
            'score' => 'required|integer|min:1|max:100',
            'description' => 'nullable|string',
        ]);

        $criteria = Criteria::findOrFail($criterion);
        $criteria->update([
            'name' => $request->name,
            'category' => $request->category,
            'score' => $request->score,
            'description' => $request->description,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('criteria.index'),
                'message' => 'Criterion updated successfully.'
            ]);
        }

        return redirect()->route('criteria.index')
            ->with('success', 'Criterion updated successfully.');
    }

    /**
     * Remove the specified criteria from storage.
     *
     * @param  \App\Models\Criteria  $criteria
     * @return \Illuminate\Http\Response
     */
    public function destroy($criterion)
    {
        try {
            // Find the criteria by ID
            $criteria = Criteria::findOrFail($criterion);
            
            // Log the incoming request data
            \Log::info('DELETE Request Data', [
                'method' => request()->method(),
                'all' => request()->all(),
                'headers' => request()->header(),
                'route_parameters' => request()->route()->parameters(),
                'criteria_id' => $criteria->id,
                'criteria' => $criteria->toArray(),
                'user_id' => auth()->id()
            ]);

            // Log the attempt to delete
            \Log::info('Attempting to delete criteria', [
                'criteria_id' => $criteria->id,
                'name' => $criteria->name,
                'category' => $criteria->category,
                'user_id' => auth()->id()
            ]);

            // Delete the criteria
            $deleted = $criteria->delete();
            
            if ($deleted) {
                \Log::info('Successfully deleted criteria', [
                    'criteria_id' => $criteria->id,
                    'name' => $criteria->name
                ]);
                
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Criteria deleted successfully.',
                        'redirect' => route('criteria.index')
                    ]);
                }
                
                return redirect()->route('criteria.index')
                    ->with('success', 'Criteria deleted successfully.');
            } else {
                $error = 'Failed to delete criteria. Please try again.';
                \Log::error($error, [
                    'criteria_id' => $criteria->id
                ]);
                
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $error
                    ], 500);
                }
                
                return redirect()->back()
                    ->with('error', $error);
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting criteria: ' . $e->getMessage(), [
                'criteria_id' => $criterion ?? 'unknown',
                'exception' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'An error occurred while deleting the criteria: ' . $e->getMessage());
        }
    }
}
