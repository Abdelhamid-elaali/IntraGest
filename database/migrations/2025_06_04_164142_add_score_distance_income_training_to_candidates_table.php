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
            if (!Schema::hasColumn('candidates', 'distance')) {
                $table->decimal('distance', 8, 2)->nullable()->after('city');
            }
            if (!Schema::hasColumn('candidates', 'income_level')) {
                $table->string('income_level')->nullable()->after('distance');
            }
            if (!Schema::hasColumn('candidates', 'training_level')) {
                $table->string('training_level')->nullable()->after('income_level');
            }
            if (!Schema::hasColumn('candidates', 'score')) {
                $table->decimal('score', 8, 2)->nullable()->after('training_level');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $columnsToDrop = [
                'distance',
                'income_level',
                'training_level',
                'score',
                'specialization',
                'family_status'
            ];
            
            // Only drop columns that exist
            $columnsToDrop = array_filter($columnsToDrop, function($column) {
                return Schema::hasColumn('candidates', $column);
            });
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
