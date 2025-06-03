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
        // Only create if it doesn't exist already
        if (!Schema::hasTable('students')) {
            Schema::create('students', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('cin')->nullable();
                $table->string('email')->unique()->nullable();
                $table->string('phone')->nullable();
                $table->text('address')->nullable();
                $table->string('place_of_residence')->nullable();
                $table->date('date_of_birth')->nullable();
                $table->date('enrollment_date')->nullable();
                $table->enum('status', ['active', 'inactive', 'graduated', 'suspended'])->default('active');
                $table->string('academic_year')->nullable();
                $table->string('specialization')->nullable();
                $table->string('educational_level')->nullable();
                $table->string('nationality')->nullable();
                $table->timestamps();
            });
        } else {
            // If the table exists, add any missing columns
            Schema::table('students', function (Blueprint $table) {
                if (!Schema::hasColumn('students', 'first_name')) {
                    $table->string('first_name')->nullable()->after('name');
                }
                if (!Schema::hasColumn('students', 'last_name')) {
                    $table->string('last_name')->nullable()->after('first_name');
                }
                if (!Schema::hasColumn('students', 'cin')) {
                    $table->string('cin')->nullable()->after('last_name');
                }
                if (!Schema::hasColumn('students', 'place_of_residence')) {
                    $table->string('place_of_residence')->nullable()->after('address');
                }
                if (!Schema::hasColumn('students', 'educational_level')) {
                    $table->string('educational_level')->nullable()->after('specialization');
                }
                if (Schema::hasColumn('students', 'entry_date') && !Schema::hasColumn('students', 'date_of_birth')) {
                    $table->renameColumn('entry_date', 'date_of_birth');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't want to drop the entire table if it existed before
        // Just remove the columns we added
        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                // Only drop columns if they exist
                $columns = [
                    'first_name', 'last_name', 'cin', 'place_of_residence', 'educational_level'
                ];
                
                foreach ($columns as $column) {
                    if (Schema::hasColumn('students', $column)) {
                        $table->dropColumn($column);
                    }
                }
                
                // Rename date_of_birth back to entry_date if needed
                if (Schema::hasColumn('students', 'date_of_birth') && !Schema::hasColumn('students', 'entry_date')) {
                    $table->renameColumn('date_of_birth', 'entry_date');
                }
            });
        }
    }
};
