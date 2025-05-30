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
        'weight',
        'description',
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
}
