<?php

use Illuminate\Support\Facades\Route;

Route::get('/temp/check-columns', function () {
    $columns = \DB::select('SHOW COLUMNS FROM students');
    return $columns;
});
