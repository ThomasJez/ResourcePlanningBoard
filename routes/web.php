<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
*/

Route::get('/', function (){return view('planningboard.planningboard');})->name('ganttchart');

Route::get('/maintainance',  'Maintainance\TermEditController@show')->name('maintainance');
Route::get('/maintainance/termedit',  'Maintainance\TermEditController@show')->name('termedit.show');
Route::post('/maintainance/termedit', 'Maintainance\TermEditController@update')->name('termedit.update');

Route::get('/maintainance/cleanup', 'Maintainance\CleanupController@show')->name('cleanup.show');
Route::delete('/maintainance/cleanup', 'Maintainance\CleanupController@update')->name('cleanup.update');
