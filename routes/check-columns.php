<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('/check-columns', function () {
    $columns = Schema::getColumnListing('students');
    return response()->json($columns);
});
