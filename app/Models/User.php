<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'city',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'string'
    ];

    protected $attributes = [
        'status' => 'active'
    ];

    // Relationships
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)
                    ->withTimestamps();
    }

    public function primaryRole(): ?Role
    {
        return $this->roles()->first();
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class, 'student_id');
    }

    public function gradesGiven(): HasMany
    {
        return $this->hasMany(Grade::class, 'grader_id');
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    public function assignedComplaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'assigned_to');
    }

    public function roomAllocation(): HasOne
    {
        return $this->hasOne(RoomAllocation::class)->latest();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function mealAttendance(): HasMany
    {
        return $this->hasMany(MealAttendance::class);
    }

    // Role Management
    public function hasRole(string|Role $role): bool
    {
        if (is_string($role)) {
            return $this->roles()->where('slug', $role)->exists();
        }
        return $this->roles()->where('id', $role->id)->exists();
    }

    public function hasAnyRole(array|string $roles): bool
    {
        $roles = is_string($roles) ? [$roles] : $roles;
        return $this->roles()->whereIn('slug', $roles)->exists();
    }

    public function hasAllRoles(array $roles): bool
    {
        $roleCount = $this->roles()->whereIn('slug', $roles)->count();
        return $roleCount === count($roles);
    }

    public function isSuperAdmin(): bool
    {
        return $this->roles()->where('is_super_admin', true)->exists();
    }

    public function isAdmin(): bool
    {
        return $this->roles()->where('is_admin', true)->exists();
    }

    public function isDirector(): bool
    {
        return $this->hasRole('director');
    }

    public function isBoardingManager(): bool
    {
        return $this->hasRole('boarding-manager');
    }

    public function isStockManager(): bool
    {
        return $this->hasRole('stock-manager');
    }

    public function isCook(): bool
    {
        return $this->hasRole('cook');
    }

    public function isIntern(): bool
    {
        return $this->hasRole('intern');
    }

    public function assignRole(string|Role|array $roles): void
    {
        if (is_string($roles)) {
            $role = Role::where('slug', $roles)->first();
            if ($role) {
                $this->roles()->syncWithoutDetaching([$role->id]);
            }
        } elseif ($roles instanceof Role) {
            $this->roles()->syncWithoutDetaching([$roles->id]);
        } elseif (is_array($roles)) {
            $roleIds = Role::whereIn('slug', 
                array_filter($roles, 'is_string')
            )->pluck('id')->merge(
                collect($roles)->filter(function($role) {
                    return $role instanceof Role;
                })->pluck('id')
            );
            if ($roleIds->isNotEmpty()) {
                $this->roles()->syncWithoutDetaching($roleIds);
            }
        }
    }

    public function removeRole(string|Role|array $roles): void
    {
        if (is_string($roles)) {
            $role = Role::where('slug', $roles)->first();
            if ($role) {
                $this->roles()->detach($role->id);
            }
        } elseif ($roles instanceof Role) {
            $this->roles()->detach($roles->id);
        } elseif (is_array($roles)) {
            $roleIds = Role::whereIn('slug', 
                array_filter($roles, 'is_string')
            )->pluck('id')->merge(
                collect($roles)->filter(function($role) {
                    return $role instanceof Role;
                })->pluck('id')
            );
            if ($roleIds->isNotEmpty()) {
                $this->roles()->detach($roleIds);
            }
        }
    }

    public function syncRoles(string|Role|array $roles): void
    {
        if (is_string($roles)) {
            $role = Role::where('slug', $roles)->first();
            if ($role) {
                $this->roles()->sync([$role->id]);
            }
        } elseif ($roles instanceof Role) {
            $this->roles()->sync([$roles->id]);
        } elseif (is_array($roles)) {
            $roleIds = Role::whereIn('slug', 
                array_filter($roles, 'is_string')
            )->pluck('id')->merge(
                collect($roles)->filter(function($role) {
                    return $role instanceof Role;
                })->pluck('id')
            );
            if ($roleIds->isNotEmpty()) {
                $this->roles()->sync($roleIds);
            }
        }
    }

    // Query Scopes
    public function scopeByRole($query, string|array $roles)
    {
        $roles = is_string($roles) ? [$roles] : $roles;
        return $query->whereHas('roles', function($q) use ($roles) {
            $q->whereIn('slug', $roles);
        });
    }

    public function scopeAdmins($query)
    {
        return $query->whereHas('roles', function($q) {
            $q->where('is_admin', true);
        });
    }

    public function scopeSuperAdmins($query)
    {
        return $query->whereHas('roles', function($q) {
            $q->where('is_super_admin', true);
        });
    }
}
