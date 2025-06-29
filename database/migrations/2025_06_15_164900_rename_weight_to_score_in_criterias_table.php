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
        Schema::table('criterias', function (Blueprint $table) {
            $table->renameColumn('weight', 'score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('criterias', function (Blueprint $table) {
            $table->renameColumn('score', 'weight');
        });
    }
};
