<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    /**
     * Display a listing of the candidates.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $candidates = Candidate::where('status', 'pending')->latest()->paginate(10);
        return view('candidates.index', compact('candidates'));
    }

    /**
     * Display a listing of accepted candidates.
     *
     * @return \Illuminate\Http\Response
     */
    public function accepted()
    {
        $candidates = Candidate::where('status', 'accepted')->latest()->paginate(10);
        return view('candidates.accepted', compact('candidates'));
    }

    /**
     * Show the form for creating a new candidate.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('candidates.create');
    }

    /**
     * Store a newly created candidate in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'distance' => 'required|numeric',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:candidates,email',
            'income_level' => 'required|string|in:low,medium,high',
            'training_level' => 'required|string|in:TS,T,Q,S',
            'has_disability' => 'required|boolean',
            'family_status' => 'required|string|in:normal,orphaned,divorced',
        ]);

        // Calculate score based on criteria
        $score = $this->calculateScore($request);
        
        $candidateData = $request->all();
        $candidateData['score'] = $score;
        $candidateData['status'] = 'pending';

        Candidate::create($candidateData);

        return redirect()->route('candidates.index')
            ->with('success', 'Candidate added successfully.');
    }

    /**
     * Calculate candidate score based on criteria.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return float
     */
    private function calculateScore(Request $request)
    {
        $score = 0;
        
        // Geographical Criteria
        $distance = $request->distance;
        $score += $distance * 0.01; // 55 km = 0.55 points
        
        // Social Criteria
        if ($request->income_level === 'low') {
            $score += 10;
        }
        
        // Academic Criteria
        switch ($request->training_level) {
            case 'S':
                $score += 20;
                break;
            case 'Q':
                $score += 15;
                break;
            case 'T':
                $score += 10;
                break;
            case 'TS':
                $score += 5;
                break;
        }
        
        // Physical Criteria
        if ($request->has_disability) {
            $score += 10;
        }
        
        // Family Criteria
        if (in_array($request->family_status, ['orphaned', 'divorced'])) {
            $score += 10;
        }
        
        return $score;
    }

    /**
     * Display the specified candidate.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function show(Candidate $candidate)
    {
        return view('candidates.show', compact('candidate'));
    }

    /**
     * Show the form for editing the specified candidate.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function edit(Candidate $candidate)
    {
        return view('candidates.edit', compact('candidate'));
    }

    /**
     * Update the specified candidate in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Candidate $candidate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'distance' => 'required|numeric',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:candidates,email,' . $candidate->id,
            'income_level' => 'required|string|in:low,medium,high',
            'training_level' => 'required|string|in:TS,T,Q,S',
            'has_disability' => 'required|boolean',
            'family_status' => 'required|string|in:normal,orphaned,divorced',
            'status' => 'required|string|in:pending,accepted,rejected',
        ]);

        // Recalculate score if criteria fields changed
        if ($candidate->distance != $request->distance ||
            $candidate->income_level != $request->income_level ||
            $candidate->training_level != $request->training_level ||
            $candidate->has_disability != $request->has_disability ||
            $candidate->family_status != $request->family_status) {
            
            $score = $this->calculateScore($request);
            $request->merge(['score' => $score]);
        }

        $candidate->update($request->all());

        // Redirect based on status
        if ($candidate->status === 'accepted') {
            return redirect()->route('candidates.accepted')
                ->with('success', 'Candidate updated successfully.');
        } else {
            return redirect()->route('candidates.index')
                ->with('success', 'Candidate updated successfully.');
        }
    }

    /**
     * Remove the specified candidate from storage.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Candidate $candidate)
    {
        $candidate->delete();

        return redirect()->route('candidates.index')
            ->with('success', 'Candidate deleted successfully.');
    }
}
