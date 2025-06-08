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
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'first_name')) {
                $table->string('first_name')->after('name');
            }
            if (!Schema::hasColumn('students', 'last_name')) {
                $table->string('last_name')->after('first_name');
            }
            if (!Schema::hasColumn('students', 'cin')) {
                $table->string('cin')->nullable()->after('last_name');
            }
            if (!Schema::hasColumn('students', 'academic_year')) {
                $table->string('academic_year')->nullable()->after('cin');
            }
            if (!Schema::hasColumn('students', 'specialization')) {
                $table->string('specialization')->nullable()->after('academic_year');
            }
            if (!Schema::hasColumn('students', 'educational_level')) {
                $table->string('educational_level')->nullable()->after('specialization');
            }
            if (!Schema::hasColumn('students', 'nationality')) {
                $table->string('nationality')->nullable()->after('educational_level');
            }
            if (!Schema::hasColumn('students', 'place_of_residence')) {
                $table->string('place_of_residence')->nullable()->after('address');
            }
            if (!Schema::hasColumn('students', 'gender')) {
                $table->string('gender', 10)->nullable()->after('place_of_residence');
            }
            
            // Make name field nullable if it's not already
            if (Schema::hasColumn('students', 'name')) {
                $table->string('name')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $columnsToDrop = [
                'first_name',
                'last_name',
                'cin',
                'academic_year',
                'specialization',
                'educational_level',
                'nationality',
                'place_of_residence',
                'gender'
            ];
            
            // Only drop columns that exist
            $columnsToDrop = array_filter($columnsToDrop, function($column) use ($table) {
                return Schema::hasColumn('students', $column);
            });
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
            
            // Revert name field to not nullable if it exists
            if (Schema::hasColumn('students', 'name')) {
                $table->string('name')->nullable(false)->change();
            }
        });
    }
};
