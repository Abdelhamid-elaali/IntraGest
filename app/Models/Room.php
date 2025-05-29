<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\RoomAllocation;
use App\Models\User;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'floor',
        'pavilion',
        'accommodation_type',
        'capacity',
        'status',
        'description',
        'maintenance_status'
    ];

    protected $casts = [
        'floor' => 'integer',
        'capacity' => 'integer'
    ];

    protected $attributes = [
        'status' => 'Available',
        'maintenance_status' => 'operational'
    ];

    // Relationships
    public function allocations()
    {
        return $this->hasMany(RoomAllocation::class);
    }

    public function currentAllocation()
    {
        return $this->hasOne(RoomAllocation::class)->where('status', 'active')->latest();
    }

    public function currentOccupants()
    {
        return $this->hasManyThrough(
            User::class,
            RoomAllocation::class,
            'room_id',
            'id',
            'id',
            'user_id'
        )->where('room_allocations.status', 'active');
    }

    // Query Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')
                    ->whereDoesntHave('allocations', function($query) {
                        $query->where('status', 'active');
                    });
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', 'occupied')
                    ->whereHas('allocations', function($query) {
                        $query->where('status', 'active');
                    });
    }

    public function scopeUnderMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeHasCapacity($query)
    {
        return $query->whereHas('currentAllocation', function($q) {
            $q->havingRaw('COUNT(user_id) < rooms.capacity');
        });
    }

    // Helper methods
    public function isAvailable(): bool
    {
        return $this->status === 'available' && !$this->currentAllocation()->exists();
    }

    public function isOccupied(): bool
    {
        return $this->status === 'occupied' && $this->currentAllocation()->exists();
    }

    public function isUnderMaintenance(): bool
    {
        return $this->status === 'maintenance';
    }

    public function hasAvailableSpace(): bool
    {
        return $this->currentOccupants()->count() < $this->capacity;
    }

    public function getOccupancyRate(): float
    {
        $occupants = $this->currentOccupants()->count();
        return $this->capacity > 0 ? ($occupants / $this->capacity) * 100 : 0;
    }

    public function getAvailableSpaces(): int
    {
        $occupants = $this->currentOccupants()->count();
        return max(0, $this->capacity - $occupants);
    }

    public function markAsAvailable(): void
    {
        $this->update(['status' => 'available']);
    }

    public function markAsOccupied(): void
    {
        $this->update(['status' => 'occupied']);
    }

    public function markUnderMaintenance(): void
    {
        $this->update(['status' => 'maintenance']);
    }
}
