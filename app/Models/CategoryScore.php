<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryScore extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'category_scores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category',
        'score',
        'weight',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'score' => 'integer',
        'weight' => 'float',
    ];

    /**
     * Get all category scores as an associative array.
     *
     * @return array
     */
    public static function getAllScores()
    {
        return static::select('category', 'score', 'weight')->get()->keyBy('category');
    }

    /**
     * Update multiple category scores at once.
     *
     * @param  array  $scores
     * @return void
     */
    public static function updateScores(array $scores)
    {
        foreach ($scores as $category => $data) {
            static::updateOrCreate(
                ['category' => $category],
                [
                    'score' => $data['score'] ?? 0,
                    'weight' => $data['weight'] ?? 0
                ]
            );
        }
    }
}
