<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('department');
            $table->integer('credits');
            $table->integer('level');
            $table->text('syllabus')->nullable();
            $table->decimal('passing_grade', 5, 2)->default(60.00);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Create prerequisite relationship table
        Schema::create('subject_prerequisites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('prerequisite_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['subject_id', 'prerequisite_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_prerequisites');
        Schema::dropIfExists('subjects');
    }
};
