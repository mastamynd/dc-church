<?php

use Illuminate\Support\Facades\Route;

/**
 * 'portal' middleware and 'portal/gl-posting-settings' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::portal('gl-posting-settings', function () {
    // Route::get('test/things', 'Main@things')->name('test.things');
    // Route::post('invoices/{invoice}/confirm', 'Main@confirm')->name('invoices.confirm');
});
