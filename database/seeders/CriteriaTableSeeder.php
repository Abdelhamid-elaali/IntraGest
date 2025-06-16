<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Criteria;
use App\Models\CategoryScore;

class CriteriaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed category scores if they don't exist
        if (CategoryScore::count() === 0) {
            $categoryScores = [
                'geographical' => 30,
                'social' => 25,
                'academic' => 20,
                'physical' => 15,
                'family' => 10
            ];

            foreach ($categoryScores as $category => $score) {
                CategoryScore::create([
                    'category' => $category,
                    'score' => $score
                ]);
            }
        }
        
        $criteria = [
            // Geographical Criteria
            [
                'name' => 'Distance from the training center',
                'category' => 'geographical',
                'score' => 30,
                'description' => 'Distance between the candidate\'s residence and the training center'
            ],
            
            // Social Criteria
            [
                'name' => 'Family income level',
                'category' => 'social',
                'score' => 25,
                'description' => 'Monthly family income level'
            ],
            [
                'name' => 'Family situation',
                'category' => 'social',
                'score' => 20,
                'description' => 'Family situation (single parent, orphan, etc.)'
            ],
            
            // Academic Criteria
            [
                'name' => 'Academic level',
                'category' => 'academic',
                'score' => 15,
                'description' => 'Current academic level of the candidate'
            ],
            [
                'name' => 'Previous training',
                'category' => 'academic',
                'score' => 10,
                'description' => 'Previous training or certifications'
            ],
            
            // Physical Criteria
            [
                'name' => 'Disability',
                'category' => 'physical',
                'score' => 20,
                'description' => 'Physical or mental disability status'
            ],
            
            // Family Criteria
            [
                'name' => 'Number of siblings',
                'category' => 'family',
                'score' => 15,
                'description' => 'Number of siblings in the family'
            ],
            [
                'name' => 'Family status',
                'category' => 'family',
                'score' => 20,
                'description' => 'Family status (divorced parents, deceased, etc.)'
            ],
        ];

        
        foreach ($criteria as $criterion) {
            Criteria::updateOrCreate(
                ['name' => $criterion['name'], 'category' => $criterion['category']],
                $criterion
            );
        }
    }
}
