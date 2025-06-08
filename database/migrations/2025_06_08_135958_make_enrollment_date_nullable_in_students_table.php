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
        // First, update existing records with null enrollment_date to current date
        \DB::table('students')
            ->whereNull('enrollment_date')
            ->update(['enrollment_date' => now()]);
            
        // Then modify the column to be nullable with a default value
        Schema::table('students', function (Blueprint $table) {
            $table->date('enrollment_date')
                  ->nullable(false)
                  ->default(now())
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->date('enrollment_date')
                  ->nullable(false)
                  ->change();
        });
    }
};
