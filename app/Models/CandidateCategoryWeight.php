<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateCategoryWeight extends Model
{
    protected $fillable = [
        'candidate_id',
        'category',
        'weight'
    ];

    /**
     * Get the candidate that owns the category weight.
     */
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
} 