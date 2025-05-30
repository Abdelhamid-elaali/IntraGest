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
        // Check if vat_rate column exists before adding it
        if (!Schema::hasColumn('stocks', 'vat_rate')) {
            Schema::table('stocks', function (Blueprint $table) {
                $table->decimal('vat_rate', 5, 2)->after('unit_price')->default(20.00);
            });
        }
        
        // Check if entry_date column exists before adding it
        if (!Schema::hasColumn('stocks', 'entry_date')) {
            Schema::table('stocks', function (Blueprint $table) {
                $table->date('entry_date')->after('unit_price')->nullable();
            });
        }
        
        // Update stock_transactions table to add missing fields
        Schema::table('stock_transactions', function (Blueprint $table) {
            // Check if columns exist before adding them
            if (!Schema::hasColumn('stock_transactions', 'unit_price')) {
                $table->decimal('unit_price', 10, 2)->after('quantity')->nullable();
            }
            
            if (!Schema::hasColumn('stock_transactions', 'reference_number')) {
                $table->string('reference_number')->after('notes')->nullable();
            }
            
            if (!Schema::hasColumn('stock_transactions', 'transaction_date')) {
                $table->timestamp('transaction_date')->after('notes')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop columns if they exist
        if (Schema::hasColumn('stocks', 'vat_rate')) {
            Schema::table('stocks', function (Blueprint $table) {
                $table->dropColumn('vat_rate');
            });
        }
        
        if (Schema::hasColumn('stocks', 'entry_date')) {
            Schema::table('stocks', function (Blueprint $table) {
                $table->dropColumn('entry_date');
            });
        }
        
        // Drop columns from stock_transactions if they exist
        Schema::table('stock_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('stock_transactions', 'unit_price')) {
                $table->dropColumn('unit_price');
            }
            
            if (Schema::hasColumn('stock_transactions', 'reference_number')) {
                $table->dropColumn('reference_number');
            }
            
            if (Schema::hasColumn('stock_transactions', 'transaction_date')) {
                $table->dropColumn('transaction_date');
            }
        });
    }
};
