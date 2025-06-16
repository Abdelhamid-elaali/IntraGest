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
            $table->enum('criteria_category', ['geographical', 'social', 'academic', 'physical', 'family'])->nullable()->after('status');
            $table->unsignedBigInteger('criteria_type')->nullable()->after('criteria_category');
            $table->foreign('criteria_type')->references('id')->on('criterias')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropForeign(['criteria_type']);
            $table->dropColumn(['criteria_category', 'criteria_type']);
        });
    }
};
