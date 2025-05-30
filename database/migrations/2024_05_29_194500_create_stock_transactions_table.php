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
            if (!Schema::hasColumn('stock_transactions', 'item_name')) {
                $table->string('item_name');
            }
            if (!Schema::hasColumn('stock_transactions', 'quantity')) {
                $table->integer('quantity');
            }
            if (!Schema::hasColumn('stock_transactions', 'unit_price')) {
                $table->decimal('unit_price', 10, 2);
            }
            if (!Schema::hasColumn('stock_transactions', 'user_id')) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->dropColumn(['item_name', 'quantity', 'unit_price', 'user_id']);
        });
    }
};
