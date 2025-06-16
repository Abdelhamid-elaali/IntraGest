<?php

use Illuminate\Support\Facades\Route;

Route::get('/test-api', function () {
    return response()->json([
        'message' => 'API is working!',
        'time' => now()->toDateTimeString(),
        'status' => 'success'
    ]);
});
