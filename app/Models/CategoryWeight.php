<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryWeight extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category',
        'weight',
    ];

    /**
     * Get all category weights as an associative array.
     *
     * @return array
     */
    public static function getAllWeights()
    {
        return static::pluck('weight', 'category')->toArray();
    }

    /**
     * Update multiple category weights at once.
     *
     * @param  array  $weights
     * @return void
     */
    public static function updateWeights(array $weights)
    {
        foreach ($weights as $category => $weight) {
            static::updateOrCreate(
                ['category' => $category],
                ['weight' => $weight]
            );
        }
    }
}
