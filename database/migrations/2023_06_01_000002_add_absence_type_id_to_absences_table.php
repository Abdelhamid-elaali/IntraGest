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
        Schema::table('absences', function (Blueprint $table) {
            // Add the new absence_type_id column
            $table->foreignId('absence_type_id')->nullable()->after('type')->constrained('absence_types')->nullOnDelete();
            
            // We're keeping the 'type' column for backward compatibility temporarily
            // It will be removed in a future migration after data migration is complete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absences', function (Blueprint $table) {
            $table->dropForeign(['absence_type_id']);
            $table->dropColumn('absence_type_id');
        });
    }
};
