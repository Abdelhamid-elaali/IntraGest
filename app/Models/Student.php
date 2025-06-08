<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'place_of_residence',
        'date_of_birth',
        'enrollment_date',
        'status',
        'academic_year',
        'specialization',
        'educational_level',
        'nationality',
        'cin',
        'gender'
    ];

    /**
     * Get the user associated with the student
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all absences for this student
     */
    public function absences()
    {
        return $this->hasMany(Absence::class);
    }
    
    /**
     * Get active absences (currently ongoing)
     */
    public function activeAbsences()
    {
        $today = Carbon::today();
        return $this->absences()
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today);
    }
    
    /**
     * Get upcoming absences (in the future)
     */
    public function upcomingAbsences()
    {
        return $this->absences()
            ->where('start_date', '>', Carbon::today())
            ->orderBy('start_date');
    }
    
    /**
     * Get absences by type
     */
    public function absencesByType($type)
    {
        return $this->absences()->where('type', $type);
    }
    
    /**
     * Get absences by status
     */
    public function absencesByStatus($status)
    {
        return $this->absences()->where('status', $status);
    }
    
    /**
     * Get absences in a specific date range
     */
    public function absencesInPeriod($startDate, $endDate)
    {
        return $this->absences()->forPeriod($startDate, $endDate);
    }
    
    /**
     * Get total absence days in a period
     */
    public function getTotalAbsenceDays($startDate = null, $endDate = null)
    {
        $query = $this->absences();
        
        if ($startDate && $endDate) {
            $query = $query->forPeriod($startDate, $endDate);
        }
        
        $absences = $query->where('type', '!=', 'late')->get();
        
        $totalDays = 0;
        foreach ($absences as $absence) {
            $totalDays += $absence->getDurationInDays();
        }
        
        return $totalDays;
    }
    
    /**
     * Check if student has excessive absences
     */
    public function hasExcessiveAbsences($threshold = 3, $days = 30)
    {
        $date = Carbon::now()->subDays($days);
        $count = $this->absences()
            ->where('start_date', '>=', $date)
            ->count();
            
        return $count >= $threshold;
    }
}
