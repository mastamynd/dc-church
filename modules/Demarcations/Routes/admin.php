<?php

use Illuminate\Support\Facades\Route;

/**
 * 'admin' middleware and 'demarcations' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::admin('demarcations', function () {
    Route::post('banks/import', 'Banks@import')->name('banks.import');
    Route::get('banks/export', 'Banks@export')->name('banks.export');
    Route::get('banks/{bank}/enable', 'Banks@enable')->name('banks.enable');
    Route::get('banks/{bank}/disable', 'Banks@disable')->name('banks.disable');
    Route::resource('banks', 'Banks');

    Route::post('region-banks/import', 'Main@importBanks')->name('region-banks.import');
    Route::post('subregion-banks/import', 'Main@importBanks')->name('subregion-banks.import');
    Route::get('banking/export', 'Main@exportBanks')->name('banking.export');

    Route::get('region', 'Main@region_index')->name('region.index');
    Route::get('subregion', 'Main@subregion_index')->name('subregion.index');
    
    Route::get('payment-made-show/{type}/{type_id}', 'Main@payment_distribution')->name('payment-made-show');
    Route::get('cheque-number-list/{cheque_number}/{type}', 'Main@view_cheque_number_list')->name('cheque-number-list');

    Route::post('pay-cheque-numbers', 'Payments@pay_cheque_numbers')->name('pay-cheque-numbers');
    Route::get('sub-region-list/fetch/{reg?}', 'Payments@sub_region_list')->name('sub-region-list');
    Route::get('payment-distribution/list/{type}/{reg?}', 'Payments@payment_distribution_list')->name('payment-distribution-list');
    Route::get('make-payments-new', 'Payments@payment_index')->name('make-payments-new');

    Route::get('{type}/create', 'Main@create')->name('create');
    Route::get('{type}/{id}', 'Main@show')->name('show');
    Route::get('{type}/{id}/edit', 'Main@edit')->name('edit');
    Route::post('set-overseer/{id}', 'Main@set_overseer')->name('set-overseer');
    Route::post('{type}/create', 'Main@store')->name('store');
    Route::post('{type}/{id}/edit', 'Main@update')->name('update');
    Route::get('export', 'Main@export')->name('export');

    Route::post('make-payments/{region}', 'Main@make_payments')->name('make-payments');
    Route::post('mark-bill-paid', 'Main@mark_paid')->name('mark-bill-paid');
    Route::post('delete/{id?}', 'Main@destroy')->name('delete');
    Route::post('stop_overseer/{id?}', 'Main@stop_overseer')->name('stop_overseer');
    

    Route::get('payments-made', 'Main@payments_made')->name('payments-made');

    Route::get('pending-payments', 'Main@pending_payments')->name('payments.pending');
    Route::get('pending-payments/bank-export', 'Main@exportBankPayments')->name('payments.bank-export');
    Route::get('pending-distribution/{div?}/{id?}', 'Main@get_pending_items_by_dem')->name('payments.distribution');
});