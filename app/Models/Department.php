<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'manager_id',
        'parent_id',
        'is_active'
    ];

    /**
     * Get the stocks associated with this department
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    /**
     * Get the manager of this department
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get the parent department
     */
    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    /**
     * Get the child departments
     */
    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }
}
