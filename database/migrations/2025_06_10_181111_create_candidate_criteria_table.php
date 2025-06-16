<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidateCriteriaTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('candidate_criteria')) {
            Schema::create('candidate_criteria', function (Blueprint $table) {
                $table->id();
                $table->foreignId('candidate_id')->constrained('candidates')->onDelete('cascade');
                $table->foreignId('criteria_id')->constrained('criterias')->onDelete('cascade');
                $table->timestamps();

                $table->unique(['candidate_id', 'criteria_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('candidate_criteria')) {
            Schema::dropIfExists('candidate_criteria');
        }
    }
}
