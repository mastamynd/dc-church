<?php

use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;

/**
 * 'admin' middleware and 'church' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::admin('church', function () {
    Route::get('churches', 'Main@index')->name('index');
    Route::get('churches/create', 'Main@create')->name('create');
    Route::get('church/{id}', 'Main@show')->name('show');
    Route::get('church/{id}/edit', 'Main@edit')->name('edit');
    Route::post('church/create', 'Main@store')->name('store');
    Route::post('church/{id}', 'Main@update')->name('update');
    Route::get('church/export', 'Main@export')->name('export');

    Route::group(['prefix'=>'pastors'], function(){
        Route::get('/', 'Pastor@index')->name('pastors.index');
        Route::get('/statements', 'Pastor@statements')->name('pastors.statements');
    });
});
