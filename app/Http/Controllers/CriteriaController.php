<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\CategoryWeight;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    /**
     * Display a listing of the criteria.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch criteria from database and group by category
        $geographicalCriteria = Criteria::where('category', 'geographical')->get();
        $socialCriteria = Criteria::where('category', 'social')->get();
        $academicCriteria = Criteria::where('category', 'academic')->get();
        $physicalCriteria = Criteria::where('category', 'physical')->get();
        $familyCriteria = Criteria::where('category', 'family')->get();
        
        // Get category weights from the database
        $categoryWeights = CategoryWeight::getAllWeights();
        
        // If no weights exist, create default ones
        if (empty($categoryWeights)) {
            $categoryWeights = [
                'geographical' => 25,
                'social' => 20,
                'academic' => 20,
                'physical' => 15,
                'family' => 20
            ];
            
            // Save default weights to the database
            foreach ($categoryWeights as $category => $weight) {
                CategoryWeight::create([
                    'category' => $category,
                    'weight' => $weight
                ]);
            }
        }
        
        return view('criteria.index', compact(
            'geographicalCriteria',
            'socialCriteria',
            'academicCriteria',
            'physicalCriteria',
            'familyCriteria',
            'categoryWeights'
        ));
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
    public function weights()
    {
        // Get the current weights from the database
        $categoryWeights = CategoryWeight::getAllWeights();
        
        // If no weights exist, create default ones
        if (empty($categoryWeights)) {
            $categoryWeights = [
                'geographical' => 25,
                'social' => 20,
                'academic' => 20,
                'physical' => 15,
                'family' => 20
            ];
            
            // Save default weights to the database
            foreach ($categoryWeights as $category => $weight) {
                CategoryWeight::create([
                    'category' => $category,
                    'weight' => $weight
                ]);
            }
        }
        
        return view('criteria.weights', compact('categoryWeights'));
    }
    
    /**
     * Update the category weights.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateWeights(Request $request)
    {
        $validated = $request->validate([
            'geographical' => 'required|integer|min:0|max:100',
            'social' => 'required|integer|min:0|max:100',
            'academic' => 'required|integer|min:0|max:100',
            'physical' => 'required|integer|min:0|max:100',
            'family' => 'required|integer|min:0|max:100',
        ]);
        
        // Save the weights to the database
        CategoryWeight::updateWeights($validated);
        
        return redirect()->route('criteria.weights')
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
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:geographical,social,academic,physical,family',
            'weight' => 'required|integer|min:1|max:100',
            'description' => 'nullable|string',
        ]);

        Criteria::create($request->all());

        return redirect()->route('criteria.index')
            ->with('success', 'Criterion added successfully.');
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
            'weight' => 'required|integer|min:1|max:100',
            'description' => 'nullable|string',
        ]);

        $criteria = Criteria::findOrFail($criterion);
        $criteria->update($request->all());

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
