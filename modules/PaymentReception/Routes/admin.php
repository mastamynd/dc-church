<?php

use Illuminate\Support\Facades\Route;

/**
 * 'admin' middleware and 'payment-reception' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::admin('payment-reception', function () {
	Route::get('receive-tithe/create', 'Main@paymentShow')->name('receive-tithe');
	Route::get('receive-tithe/{id?}', 'Main@paymentShow')->name('view-tithe');
	Route::post('receive-tithe/{id?}', 'Main@store')->name('store-tithe');
	Route::post('post-tithe/{id}', 'Main@post_payment')->name('post-payment');
	Route::get('received-tithes', 'Main@paymentIndex')->name('index');
});
