<?php

namespace App\Exports;

use App\Models\Candidate;
use Illuminate\Support\Collection;
use Rap2hpoutre\FastExcel\FastExcel;

class CandidatesExport
{
    /**
     * Get the data for export
     *
     * @return Collection
     */
    public function getData()
    {
        return Candidate::select(
            'id',
            'first_name',
            'last_name',
            'email',
            'phone',
            'nationality',
            'distance',
            'gender',
            'birth_date',
            'address',
            'city',
            'income_level',
            'training_level',
            'academic_year',
            'educational_level',
            'specialization',
            'physical_condition',
            'family_status',
            'siblings_count',
            'guardian_first_name',
            'guardian_last_name',
            'guardian_dob',
            'guardian_profession',
            'guardian_phone',
            'application_date',
            'status',
            'score',
            'created_at',
            'updated_at'
        )->get()->map(function ($candidate) {
            return [
                'First Name' => $candidate->first_name,
                'Last Name' => $candidate->last_name,
                'Email' => $candidate->email,
                'Phone' => $candidate->phone,
                'Nationality' => $candidate->nationality,
                'Distance (km)' => $candidate->distance,
                'Gender' => $candidate->gender,
                'Birth Date' => $candidate->birth_date,
                'Address' => $candidate->address,
                'City' => $candidate->city,
                'Income Level' => $candidate->income_level,
                'Training Level' => $candidate->training_level,
                'Academic Year' => $candidate->academic_year,
                'Educational Level' => $candidate->educational_level,
                'Specialization' => $candidate->specialization,
                'Physical Condition' => $candidate->physical_condition,
                'Family Status' => $candidate->family_status,
                'Siblings Count' => $candidate->siblings_count,
                'Guardian First Name' => $candidate->guardian_first_name,
                'Guardian Last Name' => $candidate->guardian_last_name,
                'Guardian DOB' => $candidate->guardian_dob,
                'Guardian Profession' => $candidate->guardian_profession,
                'Guardian Phone' => $candidate->guardian_phone,
                'Application Date' => $candidate->application_date,
                'Status' => $candidate->status,
                'Score' => $candidate->score,
                'Created At' => $candidate->created_at,
                'Updated At' => $candidate->updated_at,
            ];
        });
    }

    /**
     * Export data to Excel (XLSX) format
     * 
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportToExcel()
    {
        $data = $this->getData();
        $filename = 'candidates-export-' . now()->format('Y-m-d-H-i-s') . '.xlsx';
        
        return (new FastExcel($data))->download($filename);
    }

    /**
     * Export data to CSV format
     * 
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportToCsv()
    {
        $data = $this->getData();
        $filename = 'candidates-export-' . now()->format('Y-m-d-H-i-s') . '.csv';
        
        return (new FastExcel($data))->configureCsv(',')->download($filename);
    }

    /**
     * Get the column headers for the export
     * 
     * @return array
     */
    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Nationality',
            'Distance (km)',
            'Gender',
            'Birth Date',
            'Address',
            'City',
            'Income Level',
            'Training Level',
            'Academic Year',
            'Educational Level',
            'Specialization',
            'Physical Condition',
            'Family Status',
            'Siblings Count',
            'Guardian First Name',
            'Guardian Last Name',
            'Guardian DOB',
            'Guardian Profession',
            'Guardian Phone',
            'Application Date',
            'Status',
            'Score',
            'Created At',
            'Updated At',
        ];
    }
}