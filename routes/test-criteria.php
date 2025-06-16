<?php

use Illuminate\Support\Facades\Route;
use App\Models\Criteria;

Route::get('/test-criteria', function () {
    // Test database connection and fetch some criteria
    try {
        $geographical = Criteria::where('category', 'geographical')->get();
        $social = Criteria::where('category', 'social')->get();
        
        return [
            'geographical' => $geographical,
            'social' => $social,
            'all_categories' => Criteria::select('category')->distinct()->get()->pluck('category')
        ];
    } catch (\Exception $e) {
        return [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ];
    }
});
