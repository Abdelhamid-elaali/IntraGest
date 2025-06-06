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
            $table->string('guardian_first_name')->nullable()->after('siblings_count');
            $table->string('guardian_last_name')->nullable()->after('guardian_first_name');
            $table->date('guardian_dob')->nullable()->after('guardian_last_name');
            $table->string('guardian_profession')->nullable()->after('guardian_dob');
            $table->string('guardian_phone')->nullable()->after('guardian_profession');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn([
                'guardian_first_name',
                'guardian_last_name',
                'guardian_dob',
                'guardian_profession',
                'guardian_phone',
            ]);
        });
    }
};
