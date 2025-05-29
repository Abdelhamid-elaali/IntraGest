<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admission_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Geographic criteria
            $table->decimal('distance_score', 5, 2)->default(0); // Based on distance (e.g., 55km = 0.55 points)
            // Social criteria
            $table->boolean('low_income')->default(false); // 10 points if true
            // Academic criteria
            $table->enum('education_level', ['TS', 'T', 'Q', 'S']); // S=20, Q=15, T=10, TS=5
            // Physical criteria
            $table->boolean('has_disability')->default(false); // 10 points if true
            // Family criteria
            $table->boolean('is_orphan')->default(false); // 10 points if true
            $table->boolean('has_divorced_parents')->default(false); // 10 points if true
            // Total score
            $table->decimal('total_score', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_scores');
    }
};
