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
	Route::post('reverse-tithe/{id?}', 'Main@reverse')->name('reverse-tithe');
	Route::post('post-tithe/{id}', 'Main@post_payment')->name('post-payment');
	Route::post('delete-tithe/{id}', 'Main@destroy')->name('delete-tithe');
	Route::post('post-tithes', 'Main@post_multiple_payments')->name('post-payments');
	Route::get('received-tithes', 'Main@paymentIndex')->name('index');
	Route::get('workflow', 'Main@workflow')->name('workflow');
});
