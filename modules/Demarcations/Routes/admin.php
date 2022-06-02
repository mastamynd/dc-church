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
});
