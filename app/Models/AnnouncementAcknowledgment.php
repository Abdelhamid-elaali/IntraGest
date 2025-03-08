<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Announcement;
use App\Models\User;

class AnnouncementAcknowledgment extends Model
{
    use HasFactory;

    protected $fillable = [
        'announcement_id',
        'user_id',
        'acknowledged_at',
        'notes'
    ];

    protected $casts = [
        'acknowledged_at' => 'datetime'
    ];

    // Relationships
    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function getTimeTakenToAcknowledge()
    {
        return $this->announcement->published_at->diffForHumans($this->acknowledged_at);
    }

    public function getDaysToAcknowledge()
    {
        return $this->announcement->published_at->diffInDays($this->acknowledged_at);
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAnnouncement($query, $announcementId)
    {
        return $query->where('announcement_id', $announcementId);
    }

    public function scopeAcknowledgedAfter($query, $date)
    {
        return $query->where('acknowledged_at', '>', $date);
    }

    public function scopeAcknowledgedBefore($query, $date)
    {
        return $query->where('acknowledged_at', '<', $date);
    }

    public function scopeAcknowledgedBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('acknowledged_at', [$startDate, $endDate]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($acknowledgment) {
            if (!$acknowledgment->acknowledged_at) {
                $acknowledgment->acknowledged_at = now();
            }
        });
    }
}
