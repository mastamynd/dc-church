<?php

use Illuminate\Support\Facades\Route;

/**
 * 'admin' middleware and 'gl-posting-settings' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::admin('gl-posting-settings', function () {
    Route::get('/', 'Main@index')->name('index');
    Route::post('/', 'Main@update_gl_settings');
    Route::post('/toggle-setting/{id}', 'Main@toggle_setting_active')->name('toggle-gl-setting-active');
    Route::get('/json-account-listings/{search?}', 'Main@get_accounts')->name('get-accounts-json');

    Route::get('/online-reconsiliation', 'Main@recon_index')->name('recon-index');
    Route::get('/posting-details/{id}', 'Main@posting_details')->name('get-posting-details');

    Route::get('/fetch-de-accounts/{search?}', 'Main@fetch_de_accounts')->name('get-de-accounts');
    Route::get('/fetch-distribution-items-accounts/{contact_id}/{search?}', 'Main@fetch_dist_items')->name('fetch-dist-items');

    Route::post('/posting-details/{id}', 'Main@posting_details_update')->name('update-posting-details');
    Route::post('/post-payments/{id}', 'Main@post_payment')->name('post-payments');

    Route::get('/receipt/{id}', 'Main@receipt_print')->name('view-receipt');
	Route::get('/customers/caution-money', 'Main@caution_money')->name('customers-caution');
	Route::get('/customers/statement', 'Main@customer_statements')->name('customer-statements');

    Route::post('/add-age-band', 'Main@add_age_band')->name('add-age-band');
    Route::post('/delete-age-band/{id?}', 'Main@delete_age_band')->name('delete-age-band');

    Route::get('/credit-note/request-approval/{id}', 'Main@credit_debit_note_approval')->name('credit-debit-note-approval');
});