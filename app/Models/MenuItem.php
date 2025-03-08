<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Meal;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'dietary_info',
        'calories',
        'ingredients',
        'allergens',
        'preparation_time',
        'cost_per_serving',
        'is_available',
        'last_served_at'
    ];

    protected $casts = [
        'ingredients' => 'array',
        'allergens' => 'array',
        'calories' => 'integer',
        'cost_per_serving' => 'decimal:2',
        'is_available' => 'boolean',
        'last_served_at' => 'datetime'
    ];

    // Relationships
    public function meals()
    {
        return $this->belongsToMany(Meal::class, 'meal_menu_items')
                    ->withPivot('quantity', 'notes')
                    ->withTimestamps();
    }

    // Helper methods
    public function isVegetarian()
    {
        return str_contains(strtolower($this->dietary_info), 'vegetarian');
    }

    public function isVegan()
    {
        return str_contains(strtolower($this->dietary_info), 'vegan');
    }

    public function isGlutenFree()
    {
        return !in_array('gluten', array_map('strtolower', $this->allergens));
    }

    public function hasAllergen($allergen)
    {
        return in_array(strtolower($allergen), array_map('strtolower', $this->allergens));
    }

    public function toggleAvailability()
    {
        $this->update(['is_available' => !$this->is_available]);
    }

    public function updateLastServed()
    {
        $this->update(['last_served_at' => now()]);
    }

    public function getServingHistory($startDate = null, $endDate = null)
    {
        $query = $this->meals();

        if ($startDate) {
            $query->where('meals.date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('meals.date', '<=', $endDate);
        }

        return $query->orderBy('meals.date', 'desc')->get();
    }

    public function getTotalServings($startDate = null, $endDate = null)
    {
        $query = $this->meals();

        if ($startDate) {
            $query->where('meals.date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('meals.date', '<=', $endDate);
        }

        return $query->sum('meal_menu_items.quantity');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByDietaryInfo($query, $dietaryInfo)
    {
        return $query->where('dietary_info', 'like', "%{$dietaryInfo}%");
    }

    public function scopeWithinCalorieRange($query, $min, $max)
    {
        return $query->whereBetween('calories', [$min, $max]);
    }

    public function scopeWithinCostRange($query, $min, $max)
    {
        return $query->whereBetween('cost_per_serving', [$min, $max]);
    }

    public function scopeServedAfter($query, $date)
    {
        return $query->where('last_served_at', '>', $date);
    }

    public function scopeServedBefore($query, $date)
    {
        return $query->where('last_served_at', '<', $date);
    }

    public function scopeNotServedInDays($query, $days)
    {
        return $query->where(function ($q) use ($days) {
            $q->whereNull('last_served_at')
              ->orWhere('last_served_at', '<', now()->subDays($days));
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($menuItem) {
            if (!isset($menuItem->is_available)) {
                $menuItem->is_available = true;
            }
            if (empty($menuItem->allergens)) {
                $menuItem->allergens = [];
            }
            if (empty($menuItem->ingredients)) {
                $menuItem->ingredients = [];
            }
        });
    }
}
