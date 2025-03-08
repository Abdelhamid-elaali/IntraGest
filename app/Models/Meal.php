<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MealAttendance;
use App\Models\User;

class Meal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'date',
        'meal_type',
        'serving_time',
        'expected_attendance',
        'menu_items',
        'dietary_notes'
    ];

    protected $casts = [
        'date' => 'date',
        'serving_time' => 'datetime',
        'menu_items' => 'array'
    ];

    // Relationships
    public function attendance()
    {
        return $this->hasMany(MealAttendance::class);
    }

    public function attendees()
    {
        return $this->hasManyThrough(
            User::class,
            MealAttendance::class,
            'meal_id',
            'id',
            'id',
            'user_id'
        )->where('meal_attendance.attended', true);
    }

    // Helper methods
    public function isBreakfast()
    {
        return $this->meal_type === 'breakfast';
    }

    public function isLunch()
    {
        return $this->meal_type === 'lunch';
    }

    public function isDinner()
    {
        return $this->meal_type === 'dinner';
    }

    public function getAttendanceRate()
    {
        $actualAttendance = $this->attendees()->count();
        return $this->expected_attendance > 0 
            ? ($actualAttendance / $this->expected_attendance) * 100 
            : 0;
    }

    public function markAttendance($userId, $attended = true, $notes = null)
    {
        return $this->attendance()->updateOrCreate(
            ['user_id' => $userId],
            [
                'attended' => $attended,
                'notes' => $notes
            ]
        );
    }

    public function getAbsentees()
    {
        return $this->hasManyThrough(
            User::class,
            MealAttendance::class,
            'meal_id',
            'id',
            'id',
            'user_id'
        )->where('meal_attendance.attended', false);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->startOfDay())
                    ->orderBy('date', 'asc')
                    ->orderBy('serving_time', 'asc');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('date', now()->toDateString());
    }

    public function scopeByType($query, $type)
    {
        return $query->where('meal_type', $type);
    }
}
