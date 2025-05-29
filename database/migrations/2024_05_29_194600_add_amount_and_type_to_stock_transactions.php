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
        Schema::table('stock_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_transactions', 'amount')) {
                $table->decimal('amount', 10, 2)->after('user_id');
            }
            if (!Schema::hasColumn('stock_transactions', 'type')) {
                $table->enum('type', ['supplies', 'services', 'other'])->after('amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->dropColumn(['amount', 'type']);
        });
    }
};
