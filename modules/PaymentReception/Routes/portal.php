<?php

use Illuminate\Support\Facades\Route;

/**
 * 'portal' middleware and 'portal/payment-reception' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::portal('payment-reception', function () {
    Route::get('workflow', 'Main@workflow')->name('workflow');
    // Route::post('invoices/{invoice}/confirm', 'Main@confirm')->name('invoices.confirm');
});
