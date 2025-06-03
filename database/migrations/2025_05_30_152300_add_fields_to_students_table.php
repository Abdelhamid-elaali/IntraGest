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
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('cin')->nullable()->after('last_name');
            $table->string('academic_year')->nullable()->after('status');
            $table->string('specialization')->nullable()->after('academic_year');
            $table->string('educational_level')->nullable()->after('specialization');
            $table->string('nationality')->nullable()->after('educational_level');
            $table->string('place_of_residence')->nullable()->after('address');
            // Rename entry_date to date_of_birth
            $table->renameColumn('entry_date', 'date_of_birth');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Rename date_of_birth back to entry_date
            $table->renameColumn('date_of_birth', 'entry_date');
            
            // Drop all added columns
            $table->dropColumn([
                'first_name',
                'last_name',
                'cin',
                'academic_year',
                'specialization',
                'educational_level',
                'nationality',
                'place_of_residence'
            ]);
        });
    }
};
