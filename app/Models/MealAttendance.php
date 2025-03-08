<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Meal;
use App\Models\User;

class MealAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'meal_id',
        'user_id',
        'attended',
        'notes',
        'recorded_by'
    ];

    protected $casts = [
        'attended' => 'boolean'
    ];

    // Relationships
    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Helper methods
    public function markAttended($recorderId = null, $notes = null)
    {
        $this->update([
            'attended' => true,
            'recorded_by' => $recorderId,
            'notes' => $notes
        ]);
    }

    public function markAbsent($recorderId = null, $notes = null)
    {
        $this->update([
            'attended' => false,
            'recorded_by' => $recorderId,
            'notes' => $notes
        ]);
    }

    // Scopes
    public function scopePresent($query)
    {
        return $query->where('attended', true);
    }

    public function scopeAbsent($query)
    {
        return $query->where('attended', false);
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereHas('meal', function ($q) use ($date) {
            $q->whereDate('date', $date);
        });
    }

    public function scopeForMealType($query, $type)
    {
        return $query->whereHas('meal', function ($q) use ($type) {
            $q->where('meal_type', $type);
        });
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
