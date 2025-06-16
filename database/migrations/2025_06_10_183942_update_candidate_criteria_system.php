<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCandidateCriteriaSystem extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add a 'cin' column if it does not exist (for candidate creation validation)
        if (!Schema::hasColumn('candidates', 'cin')) {
            Schema::table('candidates', function (Blueprint $table) {
                 $table->string('cin', 255)->nullable()->after('last_name');
            });
        }

        // Rename candidate_criteria table (if it exists) to candidate_criteria_old (to avoid duplicate table error)
        if (Schema::hasTable('candidate_criteria')) {
             Schema::rename('candidate_criteria', 'candidate_criteria_old');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the 'cin' column if it was added by this migration
        if (Schema::hasColumn('candidates', 'cin')) {
             Schema::table('candidates', function (Blueprint $table) {
                  $table->dropColumn('cin');
             });
        }

        // Rename candidate_criteria_old back (if it exists) to candidate_criteria
        if (Schema::hasTable('candidate_criteria_old')) {
             Schema::rename('candidate_criteria_old', 'candidate_criteria');
        }
    }
}
