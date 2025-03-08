<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\AnnouncementAcknowledgment;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'category',
        'priority',
        'published_at',
        'expires_at',
        'published_by',
        'target_roles',
        'attachments',
        'is_pinned',
        'requires_acknowledgment'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'target_roles' => 'array',
        'attachments' => 'array',
        'is_pinned' => 'boolean',
        'requires_acknowledgment' => 'boolean'
    ];

    // Relationships
    public function publisher()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function acknowledgments()
    {
        return $this->hasMany(AnnouncementAcknowledgment::class);
    }

    // Helper methods
    public function isPublished()
    {
        return $this->published_at && $this->published_at->isPast();
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isActive()
    {
        return $this->isPublished() && !$this->isExpired();
    }

    public function isPinned()
    {
        return $this->is_pinned;
    }

    public function requiresAcknowledgment()
    {
        return $this->requires_acknowledgment;
    }

    public function isTargetedToRole($role)
    {
        return empty($this->target_roles) || in_array($role, $this->target_roles);
    }

    public function isTargetedToUser(User $user)
    {
        if (empty($this->target_roles)) {
            return true;
        }

        return $user->roles()->whereIn('name', $this->target_roles)->exists();
    }

    public function hasBeenAcknowledgedBy(User $user)
    {
        return $this->acknowledgments()
            ->where('user_id', $user->id)
            ->exists();
    }

    public function acknowledge(User $user, $notes = null)
    {
        if (!$this->hasBeenAcknowledgedBy($user)) {
            return $this->acknowledgments()->create([
                'user_id' => $user->id,
                'notes' => $notes
            ]);
        }
        return false;
    }

    public function getAcknowledgmentRate()
    {
        if (!$this->requires_acknowledgment) {
            return null;
        }

        $targetUsers = User::whereHas('roles', function ($query) {
            $query->whereIn('name', $this->target_roles);
        })->count();

        if ($targetUsers === 0) {
            return 0;
        }

        return ($this->acknowledgments()->count() / $targetUsers) * 100;
    }

    public function getPendingAcknowledgments()
    {
        if (!$this->requires_acknowledgment) {
            return collect();
        }

        return User::whereHas('roles', function ($query) {
                $query->whereIn('name', $this->target_roles);
            })
            ->whereDoesntHave('announcementAcknowledgments', function ($query) {
                $query->where('announcement_id', $this->id);
            })
            ->get();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('published_at', '<=', now())
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')
                    ->where('expires_at', '<=', now());
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeRequiringAcknowledgment($query)
    {
        return $query->where('requires_acknowledgment', true);
    }

    public function scopeForRole($query, $role)
    {
        return $query->where(function ($q) use ($role) {
            $q->whereJsonContains('target_roles', $role)
              ->orWhereJsonLength('target_roles', 0)
              ->orWhereNull('target_roles');
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($announcement) {
            if (!$announcement->published_at) {
                $announcement->published_at = now();
            }
            if (empty($announcement->target_roles)) {
                $announcement->target_roles = [];
            }
            if (empty($announcement->attachments)) {
                $announcement->attachments = [];
            }
        });
    }
}
