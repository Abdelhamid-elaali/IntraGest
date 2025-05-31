<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Stock;

class StockCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'icon',
        'color',
        'status'
    ];

    /**
     * Get the stocks that belong to this category
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'category_id');
    }

    /**
     * Get the parent category
     */
    public function parent()
    {
        return $this->belongsTo(StockCategory::class, 'parent_id');
    }

    /**
     * Get the subcategories
     */
    public function subcategories()
    {
        return $this->hasMany(StockCategory::class, 'parent_id');
    }

    /**
     * Check if this is a main category (no parent)
     */
    public function isMainCategory()
    {
        return is_null($this->parent_id);
    }

    /**
     * Get only main categories (no parent)
     */
    public function scopeMainCategories($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get only subcategories of a specific parent
     */
    public function scopeSubcategoriesOf($query, $parentId)
    {
        return $query->where('parent_id', $parentId);
    }

    /**
     * Get active categories
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
