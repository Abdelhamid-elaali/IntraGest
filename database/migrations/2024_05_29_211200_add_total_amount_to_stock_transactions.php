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
        Schema::table('stock_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_transactions', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->after('unit_price')->default(0);
            }
        });

        // Calculate total_amount for existing records
        DB::statement('UPDATE stock_transactions SET total_amount = quantity * unit_price WHERE total_amount = 0');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->dropColumn('total_amount');
        });
    }
};
