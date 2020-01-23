<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/

Route::get('/getall', 'GanttchartController@getEverything')->name('everything');

Route::get('/activity/{activity}', 'ActivityController@show')->name('activity.show');
Route::put('/activity/{activity}', 'ActivityController@update')->name('activity.update');
Route::delete('/activity/{activity}', 'ActivityController@delete')->name('activity.delete');
Route::post('/activity', 'ActivityController@store')->name('activity.store');

Route::get('/resource/{resource}', 'ResourceController@show')->name('resource.show');
Route::put('/resource/{resource}', 'ResourceController@update')->name('resource.update');
Route::delete('/resource/{resource}', 'ResourceController@delete')->name('resource.delete');
Route::post('/resource', 'ResourceController@store')->name('resource.store');

Route::get('/resource-position', 'ResourceController@showPosition')->name('resource.position.show');
Route::post('/resource-position', 'ResourceController@updatePosition')->name('resource.position.update');

Route::get('/rule/{rule}', 'RuleController@show')->name('rule.show');
Route::put('/rule/{rule}', 'RuleController@update')->name('rule.update');
Route::delete('/rule/{rule}', 'RuleController@delete')->name('rule.delete');
Route::post('/rule', 'RuleController@store')->name('rule.store');

Route::get('/rule-position', 'RuleController@showPosition')->name('rule.position.show');
Route::post('/rule-position', 'RuleController@updatePosition')->name('rule.position.update');

Route::get('/start', 'StartController@show')->name('start.show');
Route::put('/start', 'StartController@update')->name('start.update');
