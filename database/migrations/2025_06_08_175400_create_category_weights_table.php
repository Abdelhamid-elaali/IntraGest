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
        Schema::create('category_weights', function (Blueprint $table) {
            $table->id();
            $table->string('category')->unique();
            $table->integer('weight')->default(0);
            $table->timestamps();
        });
        
        // Insert default weights
        DB::table('category_weights')->insert([
            ['category' => 'geographical', 'weight' => 25, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'social', 'weight' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'academic', 'weight' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'physical', 'weight' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'family', 'weight' => 20, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_weights');
    }
};
