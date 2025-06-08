<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This migration is a fix for the candidates table
        // It will safely handle any problematic columns
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to do anything in the down method for this fix
    }

    /**
     * Check if a column exists in the table.
     */
    protected function columnExists(string $table, string $column): bool
    {
        $schema = DB::getDoctrineSchemaManager();
        return $schema->listTableDetails($table)->hasColumn($column);
    }

    /**
     * Safely drop columns if they exist.
     */
    protected function safeDropColumns(string $table, array $columns): void
    {
        $existingColumns = [];
        
        foreach ($columns as $column) {
            if ($this->columnExists($table, $column)) {
                $existingColumns[] = $column;
            }
        }

        if (!empty($existingColumns)) {
            Schema::table($table, function (Blueprint $table) use ($existingColumns) {
                $table->dropColumn($existingColumns);
            });
        }
    }
};

// This is a one-time fix for the candidates table
// It will be executed when the migration runs
Schema::table('candidates', function (Blueprint $table) {
    // This empty closure is needed to properly register the migration
});

// Register a post-migration callback to fix the columns
DB::afterCommit(function () {
    $migration = new class extends Migration {
        public function safeDropColumns($table, $columns) {
            $existingColumns = [];
            $schema = DB::getDoctrineSchemaManager();
            $tableDetails = $schema->listTableDetails($table);
            
            foreach ($columns as $column) {
                if ($tableDetails->hasColumn($column)) {
                    $existingColumns[] = $column;
                }
            }

            if (!empty($existingColumns)) {
                Schema::table($table, function (Blueprint $table) use ($existingColumns) {
                    $table->dropColumn($existingColumns);
                });
            }
        }
    };

    // List of columns that might cause issues
    $columnsToDrop = [
        'distance',
        'income_level',
        'training_level',
        'specialization',
        'family_status',
        'score'
    ];

    $migration->safeDropColumns('candidates', $columnsToDrop);
});
