<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Since we are developing single page application we need to add this

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
