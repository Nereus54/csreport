<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', ['uses' => 'ReportingController@index']);

Route::get('/report/{fd?}/{td?}', ['uses' => 'ReportingController@report']);

Route::get('/ajax-report', ['uses' => 'ReportingController@ajaxReport']);

Route::get('/list/{fd?}/{td?}/{page?}', ['uses' => 'ReportingController@viewList']);

Route::get('/ajax-list', ['uses' => 'ReportingController@ajaxList']);

Route::get('/view-transaction/{id}', ['uses' => 'ReportingController@viewTransaction']);

Route::get('/view-client/{id}', ['uses' => 'ReportingController@viewClient']);

Route::get('/view-merchant/{id}', ['uses' => 'ReportingController@viewMerchant']);