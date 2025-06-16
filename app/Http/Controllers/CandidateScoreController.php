<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Criteria;
use App\Models\CandidateCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CandidateScoreController extends Controller
{
    /**
     * Show the form for editing the candidate's scores.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $candidate = Candidate::with(['criteria'])->findOrFail($id);
        
        // Get all criteria grouped by category
        $criteriaByCategory = Criteria::all()->groupBy('category');
        
        return view('candidates.scores.edit', [
            'candidate' => $candidate,
            'criteriaByCategory' => $criteriaByCategory,
        ]);
    }

    /**
     * Update the specified candidate's scores.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $candidate = Candidate::findOrFail($id);
        
        $validated = $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'nullable|numeric|min:0|max:100',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Update or create scores for each criteria
            foreach ($validated['scores'] as $criteriaId => $score) {
                if (!is_null($score)) {
                    $candidate->criteria()->syncWithoutDetaching([
                        $criteriaId => ['score' => $score]
                    ]);
                } else {
                    // Remove the score if it's null
                    $candidate->criteria()->detach($criteriaId);
                }
            }
            
            // Recalculate the total score
            $this->calculateTotalScore($candidate);
            
            DB::commit();
            
            return redirect()->route('candidates.show', $candidate->id)
                ->with('success', 'Candidate scores updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating candidate scores: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'An error occurred while updating the scores. Please try again.');
        }
    }
    
    /**
     * Calculate the total score for a candidate based on criteria scores and weights.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return void
     */
    protected function calculateTotalScore(Candidate $candidate)
    {
        $totalScore = 0;
        $totalWeight = 0;
        
        // Get all criteria with pivot data
        $criteriaScores = $candidate->criteria()
            ->wherePivot('score', '!=', null)
            ->get();
        
        // Calculate weighted score
        foreach ($criteriaScores as $criteria) {
            $weight = $criteria->weight ?? 0;
            $score = $criteria->pivot->score ?? 0;
            
            $totalScore += ($score * $weight) / 100;
            $totalWeight += $weight;
        }
        
        // Calculate average score if there are any criteria with weight
        $averageScore = $totalWeight > 0 ? ($totalScore / $totalWeight) * 100 : 0;
        
        // Update the candidate's total score
        $candidate->update([
            'score' => round($averageScore, 2)
        ]);
    }
}
