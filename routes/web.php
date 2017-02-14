<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::resource('events','EventsController');

Route::resource('bookings','BookingsController');

Route::get('/view-event/{id}', 'HomeController@viewEvent');

Route::post('imageUploadForm', 'HomeController@updateProofOfPayment' );

Route::get('/booking/create-event-booking/{id}', 'BookingsController@createEventBooking');

Route::get('/events/{id}/submitEvent', 'EventsController@submitEvent');

Route::get('/home', 'HomeController@index');

// Verification function of HomeController
Route::post('/verification', 'HomeController@verification');

