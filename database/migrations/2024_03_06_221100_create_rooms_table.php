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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number')->unique(); // Chambre N°
            $table->integer('floor'); // Étage
            $table->enum('pavilion', ['Girls', 'Boys']); // Pavillon
            $table->enum('accommodation_type', ['Personal', 'Shared']); // Type d'hébergement
            $table->integer('capacity'); // Nombre de stagiaires
            $table->enum('status', ['Available', 'Unavailable'])->default('Available'); // Statut
            $table->enum('description', ['Occupied - Reservable', 'Vacant - Trainees'])->nullable(); // Description
            $table->enum('maintenance_status', ['operational', 'maintenance'])->default('operational'); // For maintenance tracking
            $table->timestamps();
        });

        // Create room_allocations table for managing room assignments
        Schema::create('room_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Add index for common queries
            $table->index(['status', 'start_date', 'end_date']);
            $table->index(['room_id', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_allocations');
        Schema::dropIfExists('rooms');
    }
};
