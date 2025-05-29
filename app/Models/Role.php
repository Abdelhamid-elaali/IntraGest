<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'permissions',
        'is_admin',
        'is_super_admin'
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_admin' => 'boolean',
        'is_super_admin' => 'boolean'
    ];

    // Relationships
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
                    ->withTimestamps();
    }

    // Helper Methods
    public function assignToUser(User $user): void
    {
        $this->users()->syncWithoutDetaching([$user->id]);
    }

    public function removeFromUser(User $user): void
    {
        $this->users()->detach($user->id);
    }

    // Role Checks
    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin;
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    // Query Scopes
    public function scopeByName($query, string $name)
    {
        return $query->where('name', $name);
    }

    public function scopeBySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }

    public function scopeAdmins($query)
    {
        return $query->where('is_admin', true);
    }

    public function scopeSuperAdmins($query)
    {
        return $query->where('is_super_admin', true);
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Static Methods
    public static function findByName(string $name): ?self
    {
        return static::where('name', $name)->first();
    }

    public static function findBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->first();
    }
}
