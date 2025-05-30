<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
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
        // Sample criteria data - in a real application, this would come from the database
        $geographicalCriteria = [
            (object)[
                'id' => 1,
                'name' => 'Distance from Institution',
                'weight' => 30,
                'category' => 'geographical'
            ],
            (object)[
                'id' => 2,
                'name' => 'Rural Area',
                'weight' => 20,
                'category' => 'geographical'
            ]
        ];
        
        $socialCriteria = [
            (object)[
                'id' => 3,
                'name' => 'Low Income',
                'weight' => 25,
                'category' => 'social'
            ],
            (object)[
                'id' => 4,
                'name' => 'Special Social Needs',
                'weight' => 15,
                'category' => 'social'
            ]
        ];
        
        $academicCriteria = [
            (object)[
                'id' => 5,
                'name' => 'Training Level',
                'weight' => 20,
                'category' => 'academic'
            ],
            (object)[
                'id' => 6,
                'name' => 'Academic Performance',
                'weight' => 15,
                'category' => 'academic'
            ]
        ];
        
        $physicalCriteria = [
            (object)[
                'id' => 7,
                'name' => 'Disability Status',
                'weight' => 25,
                'category' => 'physical'
            ]
        ];
        
        $familyCriteria = [
            (object)[
                'id' => 8,
                'name' => 'Family Structure',
                'weight' => 20,
                'category' => 'family'
            ],
            (object)[
                'id' => 9,
                'name' => 'Number of Siblings',
                'weight' => 10,
                'category' => 'family'
            ]
        ];
        
        $categoryWeights = [
            'geographical' => 25,
            'social' => 20,
            'academic' => 20,
            'physical' => 15,
            'family' => 20
        ];
        
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
        $categoryWeights = [
            'geographical' => 25,
            'social' => 20,
            'academic' => 20,
            'physical' => 15,
            'family' => 20
        ];
        
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
        $request->validate([
            'geographical' => 'required|integer|min:0|max:100',
            'social' => 'required|integer|min:0|max:100',
            'academic' => 'required|integer|min:0|max:100',
            'physical' => 'required|integer|min:0|max:100',
            'family' => 'required|integer|min:0|max:100',
        ]);
        
        // In a real application, you would save these values to the database
        // For now, we'll just redirect back with a success message
        
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
    public function edit(Criteria $criteria)
    {
        return view('criteria.edit', compact('criteria'));
    }

    /**
     * Update the specified criteria in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Criteria  $criteria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Criteria $criteria)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:geographical,social,academic,physical,family',
            'weight' => 'required|integer|min:1|max:100',
            'description' => 'nullable|string',
        ]);

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
    public function destroy(Criteria $criteria)
    {
        $criteria->delete();

        return redirect()->route('criteria.index')
            ->with('success', 'Criteria deleted successfully.');
    }
}
