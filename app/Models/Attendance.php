<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Absence;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'recorded_by',
        'location',
        'notes',
        'late_reason',
        'early_departure_reason'
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function absenceRecord()
    {
        return $this->hasOne(Absence::class);
    }

    // Helper methods
    public function getDuration()
    {
        if (!$this->check_in || !$this->check_out) {
            return null;
        }
        return $this->check_in->diffInMinutes($this->check_out);
    }

    public function isLate($threshold = 15)
    {
        if (!$this->check_in) {
            return false;
        }

        $expectedTime = $this->date->copy()->setTime(8, 0); // Assuming school starts at 8 AM
        return $this->check_in->diffInMinutes($expectedTime) > $threshold;
    }

    public function isEarlyDeparture($threshold = 15)
    {
        if (!$this->check_out) {
            return false;
        }

        $expectedTime = $this->date->copy()->setTime(16, 0); // Assuming school ends at 4 PM
        return $expectedTime->diffInMinutes($this->check_out) > $threshold;
    }

    public function isPresent()
    {
        return $this->status === 'present';
    }

    public function isAbsent()
    {
        return $this->status === 'absent';
    }

    public function isExcused()
    {
        return $this->status === 'excused';
    }

    public function markAsPresent($checkInTime = null)
    {
        $this->update([
            'status' => 'present',
            'check_in' => $checkInTime ?? now()
        ]);
    }

    public function markAsAbsent($reason = null)
    {
        $this->update([
            'status' => 'absent',
            'notes' => $reason
        ]);
    }

    public function markAsExcused($reason)
    {
        $this->update([
            'status' => 'excused',
            'notes' => $reason
        ]);
    }

    public function checkOut($time = null)
    {
        if ($this->isPresent()) {
            $this->update(['check_out' => $time ?? now()]);
        }
    }

    // Scopes
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByLocation($query, $location)
    {
        return $query->where('location', $location);
    }

    public function scopeLate($query, $threshold = 15)
    {
        $expectedTime = now()->setTime(8, 0);
        return $query->whereNotNull('check_in')
                    ->whereRaw("TIME_TO_SEC(TIMEDIFF(check_in, '{$expectedTime->format('H:i:s')}')) > ?", [$threshold * 60]);
    }

    public function scopeEarlyDeparture($query, $threshold = 15)
    {
        $expectedTime = now()->setTime(16, 0);
        return $query->whereNotNull('check_out')
                    ->whereRaw("TIME_TO_SEC(TIMEDIFF('{$expectedTime->format('H:i:s')}', check_out)) > ?", [$threshold * 60]);
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('user_id', $studentId);
    }

    public function scopeRecordedBy($query, $recorderId)
    {
        return $query->where('recorded_by', $recorderId);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($attendance) {
            if (!$attendance->recorded_by) {
                $attendance->recorded_by = auth()->id();
            }
            if (!$attendance->status) {
                $attendance->status = 'present';
            }
        });
    }
}
