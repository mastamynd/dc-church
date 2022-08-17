<?php

use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;

/**
 * 'admin' middleware and 'church' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::admin('church', function () {
    //Churches Routes
    Route::get('churches', 'Main@index')->name('index');
    Route::get('churches/create', 'Main@create')->name('create');
    Route::get('church/{id}', 'Main@show')->name('show');
    Route::get('church/{id}/edit', 'Main@edit')->name('edit');
    Route::post('church/create', 'Main@store')->name('store');
    Route::post('church/{id}', 'Main@update')->name('update');
    Route::get('church/export', 'Main@export')->name('export');
    Route::post('church/assign-subregion/{id}', 'Demarcation@assign')->name('assign_subregion');
    Route::get('church/fetch-demarcations/{id}/{search?}', 'Demarcation@fetch_demarcations')->name('fetch_demarcations');
    
    


    //Pastors Routes
    Route::get('pastors/index', 'Pastor@indexPastors')->name('pastors.index');
    Route::get('pastors/create/new', 'Pastor@createPastor')->name('pastors.create');
    Route::get('pastors/statements/{year?}/{month?}', 'Pastor@statements')->name('pastors.statements');
    Route::post('/pastor/store/new','Pastor@storePastors')->name('pastors.store');
    Route::get('pastors/show/{id}','Pastor@showPastor')->name('pastors.show');
    Route::get('pastors/edit/{id}','Pastor@editPastor')->name('pastors.edit');
    Route::post('/pastor/update','Pastor@updatePastor')->name('pastor.update');
    Route::post('/pastor/add/history','Pastor@add_pastor_history')->name('pastors.add.history');

    Route::get('pastors/filter/{search?}', 'Pastor@get_pastors')->name('fetch_pastors');
    Route::post('pastors/add-to-church/{id}', 'Pastor@add_to_church')->name('add_to_church');
    Route::get('filter/churches/list/{search?}','Pastor@get_church')->name('get_church');
    Route::post('pastors/stop_history/{id?}', 'Pastor@stop_history')->name('pastor_stop_history');

    //Demarcations Routes
    Route::get('demarcations/{type}', 'Demarcation@index')->name('demarcations.index');

    Route::get('settings/edit', 'Settings@edit')->name('settings.edit');
    Route::post('settings/update', 'Settings@update')->name('settings.update');

    // Report Routes
    Route::get('report/regionalSummaryIndex','Reports\RegionalSummary@paymentDivisionSummaryIndex')->name('paymentDivisionSummaryIndex');
    Route::get('report/regional/PaymentDivisionSummary/{year}','Reports\RegionalSummary@regionalPaymentDivisionSummary')->name('regionalPaymentDivisionSummary');
    Route::get('report/subRegionPaymentDivisionSummary/{year?}','Reports\RegionalSummary@subRegionPaymentDivisionSummary')->name('subRegionPaymentDivisionSummary');

    Route::get('report/yearly/returns/record/{year?}','Reports\YearReturnRecords@reportIndex')->name('yearly-returns-records');

    Route::get('report/comparison-table/{year?}','Reports\ComparisonTables@reportindex')->name('comparison-table-report');

    Route::get('report/national-analysis/{year?}','Reports\NationalAnalysis@reportindex')->name('national-analysis-report');
});
