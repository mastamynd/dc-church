<?php
use Illuminate\Support\Facades\Route;

/**
 * 'admin' middleware and 'payment-reception' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::api('payment-reception', function () {
	Route::get('get-church-by-code', 'API\Main@church')->name('get-church-by-code');
	Route::post('receive-church-payment', 'API\Main@reception')->name('receive-church-payment');
});
