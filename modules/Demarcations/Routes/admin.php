<?php

use Illuminate\Support\Facades\Route;

/**
 * 'admin' middleware and 'demarcations' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::admin('demarcations', function () {
    Route::get('region', 'Main@region_index')->name('region.index');
    Route::get('subregion', 'Main@subregion_index')->name('subregion.index');
    Route::get('{type}/create', 'Main@create')->name('create');
    Route::get('{type}/{id}', 'Main@show')->name('show');
    Route::get('{type}/{id}/edit', 'Main@edit')->name('edit');
    Route::post('set-overseer/{id}', 'Main@set_overseer')->name('set-overseer');
    Route::post('{type}/create', 'Main@store')->name('store');
    Route::post('{type}/{id}/edit', 'Main@update')->name('update');
    Route::get('export', 'Main@export')->name('export');

    Route::post('make-payments/{region}', 'Main@make_payments')->name('make-payments');
    Route::post('mark-bill-paid', 'Main@mark_paid')->name('mark-bill-paid');
    Route::post('delete/{id}', 'Main@destroy')->name('delete');

    Route::get('payments-made', 'Main@payments_made')->name('payments-made');

    Route::get('pending-payments', 'Main@pending_payments')->name('payments.pending');
    Route::get('pending-distribution/{div?}/{id?}', 'Main@get_pending_items_by_dem')->name('payments.distribution');
});