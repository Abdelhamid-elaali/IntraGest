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
        if (!Schema::hasTable('subject_prerequisites')) {
            Schema::create('subject_prerequisites', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subject_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('prerequisite_id')->nullable()->constrained('subjects')->onDelete('cascade');
                $table->timestamps();

                // Prevent duplicate prerequisites
                $table->unique(['subject_id', 'prerequisite_id'], 'subject_prerequisite_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_prerequisites');
    }
};