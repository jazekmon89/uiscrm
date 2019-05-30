<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('/quotes/forms/{OrganisationID?}', 'QuotesApiController@forms')
	->name('api.quotes.forms');
Route::get('/quotes/form/{PolicyTypeID}/{OrganisationID?}', 'QuotesApiController@getForm')
	->name('api.quotes.form');

/**
* @param PolicyTypeID
* @param FormtypeID
*/
Route::get('/quotes/form/{PolicyTypeID}/{FormTypeID}/groups', 'QuotesApiController@groups')
	->name('api.quotes.groups');

Route::get('/quotes/form/{PolicyTypeID}/{FormTypeID}/html/{generate?}', 'QuotesApiController@html')
	->name('api.quotes.html');

Route::match(['get', 'post'], '/quotes/form/{PolicyTypeID}/{FormTypeID}/validate/{GroupID}', 'QuotesApiController@validateForm')
	->name('api.quotes.validate');

Route::match(['get', 'post'], '/quotes/form/{PolicyTypeID}/{FormTypeID}/submit', 'QuotesApiController@submit')
	->name('api.quotes.submit');