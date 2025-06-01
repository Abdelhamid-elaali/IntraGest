<?php

namespace Database\Seeders;

use App\Models\AbsenceType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class AbsenceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Sick Leave',
                'description' => 'Absence due to illness or medical appointments',
                'color' => '#e53e3e', // red
                'requires_documentation' => true,
                'max_days_allowed' => 10,
            ],
            [
                'name' => 'Personal Leave',
                'description' => 'Absence for personal reasons',
                'color' => '#3182ce', // blue
                'requires_documentation' => false,
                'max_days_allowed' => 5,
            ],
            [
                'name' => 'Family Emergency',
                'description' => 'Absence due to family emergencies',
                'color' => '#dd6b20', // orange
                'requires_documentation' => true,
                'max_days_allowed' => 7,
            ],
            [
                'name' => 'Vacation',
                'description' => 'Planned vacation time',
                'color' => '#38a169', // green
                'requires_documentation' => false,
                'max_days_allowed' => 15,
            ],
            [
                'name' => 'Late Arrival',
                'description' => 'Late arrival to class or school',
                'color' => '#805ad5', // purple
                'requires_documentation' => false,
                'max_days_allowed' => null,
            ],
        ];

        foreach ($types as $type) {
            // Check if the type already exists to avoid duplicates
            if (!AbsenceType::where('name', $type['name'])->exists()) {
                AbsenceType::create($type);
            }
        }
    }
}
