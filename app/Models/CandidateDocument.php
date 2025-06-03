<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateDocument extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'candidate_id',
        'filename',
        'original_filename',
        'file_path',
        'file_type',
        'file_size',
        'document_type',
    ];

    /**
     * Get the candidate that owns the document.
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Get the full storage path of the document.
     *
     * @return string
     */
    public function getFullPathAttribute(): string
    {
        return storage_path('app/public/' . $this->file_path);
    }

    /**
     * Get the public URL of the document.
     *
     * @return string
     */
    public function getPublicUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
