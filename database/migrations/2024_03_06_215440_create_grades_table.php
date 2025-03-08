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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('academic_term_id')->constrained('academic_terms')->onDelete('cascade');
            $table->foreignId('grader_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('score', 5, 2);
            $table->string('letter_grade', 2)->nullable();
            $table->decimal('grade_point', 3, 2)->nullable();
            $table->string('assessment_type');
            $table->decimal('weight', 5, 2)->default(100.00);
            $table->text('comments')->nullable();
            $table->boolean('is_final')->default(false);
            $table->timestamp('finalized_at')->nullable();
            $table->timestamps();

            // Create a shorter index name manually
            $table->unique(
                ['student_id', 'subject_id', 'academic_term_id', 'assessment_type'],
                'grades_composite_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
