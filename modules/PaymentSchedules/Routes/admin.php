<?php

use Illuminate\Support\Facades\Route;

/**
 * 'admin' middleware and 'payment-schedules' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */
Route::admin('payment-schedules', function () {
    Route::get('approvals', 'Approvals@edit')->name('approvals.edit');
    Route::post('approvals', 'Approvals@update')->name('approvals.update');

    Route::get('cycles', 'Cycles@index')->name('cycles.index');
    Route::get('cycles/create', 'Cycles@create')->name('cycles.create');
    Route::post('cycles', 'Cycles@store')->name('cycles.store');
    Route::get('cycles/{cycle}', 'Cycles@show')->name('cycles.show');
    Route::get('cycles/{cycle}/edit', 'Cycles@edit')->name('cycles.edit');
    Route::patch('cycles/{cycle}', 'Cycles@update')->name('cycles.update');
    Route::delete('cycles/{cycle}', 'Cycles@destroy')->name('cycles.destroy');
    Route::post('cycles/{cycle}/close', 'Cycles@close')->name('cycles.close');

    Route::get('cycles/{cycle}/schedules/create/{type}', 'Schedules@create')->name('schedules.create');
    Route::post('cycles/{cycle}/schedules/{type}', 'Schedules@store')->name('schedules.store');
    Route::get('schedules/{schedule}', 'Schedules@show')->name('schedules.show');
    Route::get('schedules/{schedule}/items/{item}', 'Schedules@item')->name('schedules.item');
    Route::post('schedules/{schedule}/submit', 'Schedules@submit')->name('schedules.submit');
    Route::post('schedules/{schedule}/approve', 'Schedules@approve')->name('schedules.approve');
    Route::post('schedules/{schedule}/reject', 'Schedules@reject')->name('schedules.reject');
    Route::post('schedules/{schedule}/cancel', 'Schedules@cancel')->name('schedules.cancel');
    Route::post('schedules/{schedule}/otp/send', 'Schedules@sendOtp')->name('schedules.otp.send');
    Route::post('schedules/{schedule}/otp/verify', 'Schedules@verifyOtp')->name('schedules.otp.verify');
});
