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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->text('description')->nullable();
            $table->integer('quantity');
            $table->decimal('stock_percentage', 5, 2)->storedAs('(quantity * 100.0) / NULLIF(maximum_quantity, 0)');
            $table->integer('maximum_quantity');
            $table->integer('minimum_quantity')->default(10);
            $table->enum('alert_status', ['red', 'yellow', 'green'])->virtualAs(
                "CASE 
                    WHEN stock_percentage <= 10 THEN 'red'
                    WHEN stock_percentage <= 15 THEN 'yellow'
                    ELSE 'green'
                END"
            );
            $table->decimal('unit_price', 10, 2);
            $table->string('unit_type'); // kg, pieces, etc.
            $table->date('expiry_date')->nullable();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Create stock_transactions table for tracking stock movements
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained()->onDelete('cascade');
            $table->string('type'); // in, out
            $table->integer('quantity');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // who made the transaction
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
        Schema::dropIfExists('stocks');
    }
};
