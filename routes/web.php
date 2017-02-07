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

Route::get('/', function () {
    return view('welcome');
});

Route::get('getAnalyticsData', array('as' => 'google.statistics.index', 'uses' => 'AnalyticsController@index'));
Route::post('getAnalyticsData', array('as' => 'google.statistics.ajax', 'uses' => 'AnalyticsController@getData'));