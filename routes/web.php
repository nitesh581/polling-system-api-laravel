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

// Add User
Route::post('/add_user', 'UserController@addUser');

// Login User
Route::post('/login', 'UserController@loginUser');

// List All Users
Route::get('/list_users', 'UserController@listUsers');

// Add Poll
Route::post('/add_poll', 'UserController@addPoll');

// List Polls
Route::get('/list_polls', 'UserController@listPolls');

// List a Poll
Route::get('/list_poll/{id}', 'UserController@listPoll');

// Vote Api
Route::put('/vote/{id}/{opt_id}', 'UserController@doVote');

// Add Poll Option
Route::post('/add_poll_option/{id}', 'UserController@addOption');

// Delete Poll Option
Route::delete('/delete_poll_option/{id}/{opt_id}', 'UserController@deleteOption');
