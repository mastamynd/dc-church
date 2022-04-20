<?php

use Illuminate\Support\Facades\Route;

/**
 * 'admin' middleware and 'church' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::admin('church', function () {
    Route::get('/', 'Main@index');
});
