<?php

namespace App\Console\Commands;

use App\Models\Candidate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateCandidateScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'candidates:update-scores';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all candidate scores based on their criteria scores';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $candidates = Candidate::with('criteria')->get();
        $bar = $this->output->createProgressBar(count($candidates));
        
        $this->info('Updating candidate scores...');
        
        $updatedCount = 0;
        
        foreach ($candidates as $candidate) {
            try {
                $totalScore = 0;
                $totalWeight = 0;
                $hasScores = false;
                
                // Calculate weighted score
                foreach ($candidate->criteria as $criteria) {
                    if (isset($criteria->pivot->score) && $criteria->pivot->score !== null) {
                        $weight = $criteria->weight ?? 0;
                        $score = $criteria->pivot->score;
                        
                        $totalScore += ($score * $weight) / 100;
                        $totalWeight += $weight;
                        $hasScores = true;
                    }
                }
                
                // Calculate average score if there are any criteria with weight
                $averageScore = $hasScores && $totalWeight > 0 ? ($totalScore / $totalWeight) * 100 : null;
                
                // Update the candidate's total score if it has changed
                if ($candidate->score != $averageScore) {
                    $candidate->update([
                        'score' => $averageScore !== null ? round($averageScore, 2) : null
                    ]);
                    $updatedCount++;
                }
                
                $bar->advance();
                
            } catch (\Exception $e) {
                Log::error("Error updating score for candidate {$candidate->id}: " . $e->getMessage());
                $this->error("Error updating score for candidate {$candidate->id}: " . $e->getMessage());
            }
        }
        
        $bar->finish();
        $this->newLine();
        
        $this->info("\nUpdated scores for {$updatedCount} candidates.");
        
        return Command::SUCCESS;
    }
}
