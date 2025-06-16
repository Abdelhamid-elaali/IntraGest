<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the table if it exists
        Schema::dropIfExists('category_scores');
        
        // Create the table with the correct structure
        Schema::create('category_scores', function (Blueprint $table) {
            $table->id();
            $table->string('category')->unique();
            $table->decimal('score', 5, 2)->default(0);
            $table->decimal('weight', 5, 2)->default(0);
            $table->timestamps();
        });
        
        // Insert default categories with 0 scores and equal weights
        $categories = [
            'academic',
            'financial',
            'distance',
            'family_status',
            'siblings_count',
            'physical_condition',
            'special_needs',
            'other'
        ];
        
        $weight = 100 / count($categories);
        
        foreach ($categories as $category) {
            DB::table('category_scores')->insert([
                'category' => $category,
                'score' => 0,
                'weight' => $weight,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_scores');
    }
};
