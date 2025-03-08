<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_terms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // semester, trimester, quarter, etc.
            $table->string('academic_year');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('registration_deadline');
            $table->date('drop_deadline');
            $table->date('grading_deadline');
            $table->boolean('is_current')->default(false);
            $table->string('status')->default('upcoming'); // upcoming, active, completed
            $table->timestamps();

            // Ensure only one current term
            $table->unique('is_current', 'unique_current_term');
        });

        // Create default academic term
        DB::table('academic_terms')->insert([
            'name' => '2024-2025 First Semester',
            'type' => 'semester',
            'academic_year' => '2024-2025',
            'start_date' => '2024-09-01',
            'end_date' => '2024-12-20',
            'registration_deadline' => '2024-08-25',
            'drop_deadline' => '2024-10-15',
            'grading_deadline' => '2024-12-30',
            'is_current' => true,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_terms');
    }
};
