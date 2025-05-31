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
        Schema::table('stocks', function (Blueprint $table) {
            // Add new category relationship fields
            $table->foreignId('category_id')->nullable()->after('category')->constrained('stock_categories')->onDelete('set null');
            $table->foreignId('subcategory_id')->nullable()->after('category_id')->constrained('stock_categories')->onDelete('set null');
            
            // Add additional fields for enhanced stock management
            $table->string('location')->nullable()->after('department_id');
            $table->string('barcode')->nullable()->after('code');
            $table->string('image')->nullable()->after('description');
            $table->decimal('vat_rate', 5, 2)->default(20.00)->after('unit_price'); // Default 20% VAT
            $table->enum('status', ['active', 'discontinued', 'pending'])->default('active')->after('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            // Remove the added fields
            $table->dropForeign(['category_id']);
            $table->dropForeign(['subcategory_id']);
            $table->dropColumn([
                'category_id',
                'subcategory_id',
                'location',
                'barcode',
                'image',
                'vat_rate',
                'status'
            ]);
        });
    }
};
