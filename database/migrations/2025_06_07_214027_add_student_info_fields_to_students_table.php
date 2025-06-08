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
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('students', 'academic_year')) {
                $table->string('academic_year')->nullable()->after('enrollment_date');
            }
            
            if (!Schema::hasColumn('students', 'specialization')) {
                $table->string('specialization')->nullable()->after('academic_year');
            }
            
            if (!Schema::hasColumn('students', 'nationality')) {
                $table->string('nationality')->nullable()->after('specialization');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['academic_year', 'specialization', 'nationality']);
        });
    }
};
