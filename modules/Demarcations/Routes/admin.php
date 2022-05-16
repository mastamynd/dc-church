<?php

use Illuminate\Support\Facades\Route;

/**
 * 'admin' middleware and 'demarcations' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::admin('demarcations', function () {
    Route::get('/', 'Main@index');
});
