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
        Schema::table('students', function (Blueprint $table) {
            $table->string('academic_year')->nullable()->after('status');
            $table->string('specialization')->nullable()->after('academic_year');
            $table->string('nationality')->nullable()->after('specialization');
            $table->date('entry_date')->nullable()->after('nationality');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['academic_year', 'specialization', 'nationality', 'entry_date']);
        });
    }
};
