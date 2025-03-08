<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('term_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_term_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('active'); // active, inactive
            $table->integer('capacity')->default(30);
            $table->integer('enrolled_count')->default(0);
            $table->timestamps();

            $table->unique(['academic_term_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('term_subjects');
    }
};
