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
        Schema::table('candidates', function (Blueprint $table) {
            $table->float('distance')->nullable();
            $table->string('income_level')->nullable();
            $table->string('training_level')->nullable();
            $table->string('specialization')->nullable();
            $table->string('family_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn(['distance', 'income_level', 'training_level', 'specialization', 'family_status']);
        });
    }
};
