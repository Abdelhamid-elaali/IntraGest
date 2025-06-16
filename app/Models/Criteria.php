<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'category',
        'score',
        'description',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'score' => 'float',
    ];

    /**
     * The categories available for criteria.
     *
     * @var array
     */
    public static $categories = [
        'geographical' => 'Geographical',
        'social' => 'Social',
        'academic' => 'Academic',
        'physical' => 'Physical',
        'family' => 'Family',
    ];

    /**
     * Get the formatted category name.
     *
     * @return string
     */
    public function getCategoryNameAttribute()
    {
        return self::$categories[$this->category] ?? ucfirst($this->category);
    }
    
    /**
     * Scope a query to only include criteria of a given category.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
    
    /**
     * Get the candidates associated with this criteria.
     */
    public function candidates()
    {
        return $this->belongsToMany(Candidate::class, 'candidate_criteria')
            ->withPivot('score')
            ->withTimestamps();
    }
}
