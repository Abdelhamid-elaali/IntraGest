<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CriteriaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API routes
Route::middleware(['api'])->group(function () {
    // Criteria API
    Route::prefix('criteria')->name('api.criteria.')->group(function () {
        Route::get('/', [CriteriaController::class, 'getByCategory'])
            ->name('by-category')
            ->middleware('throttle:60,1') // Rate limiting: 60 requests per minute
            ->withoutMiddleware(['auth:sanctum']); // Ensure this route is accessible without authentication
    });
});

// Protected API routes (require authentication)
Route::middleware(['auth:sanctum'])->group(function () {
    // Add authenticated API routes here if needed
});
