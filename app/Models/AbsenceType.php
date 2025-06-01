<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenceType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'color',
        'requires_documentation',
        'max_days_allowed',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'requires_documentation' => 'boolean',
        'max_days_allowed' => 'integer',
    ];

    /**
     * Get the absences associated with this absence type.
     */
    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    /**
     * Get the user who created this absence type.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this absence type.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
