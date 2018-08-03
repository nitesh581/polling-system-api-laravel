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
Route::get('/list_users', 'UserController@listUsers')->middleware('getUserRole');

// Add Poll
Route::post('/add_poll', 'UserController@addPoll')->middleware('getUserId');

// List Polls
Route::get('/list_polls', 'UserController@listPolls')->middleware('getUserRole');

// List a Poll
Route::get('/list_poll', 'UserController@listPoll')->middleware('getUserId');

// Vote Api
Route::put('/vote/{poll_id}/{opt_id}', 'UserController@doVote');

// Add Poll Option
Route::post('/add_poll_option/{poll_id}', 'UserController@addOption')->middleware('getUserId');

// Delete Poll Option
Route::delete('/delete_poll_option/{poll_id}/{opt_id}', 'UserController@deleteOption')->middleware('getUserId');

// Update Poll Title
Route::put('/update_poll_title/{poll_id}', 'UserController@updatePollTitle')->middleware('getUserId');

// Delete Poll
Route::delete('/delete_poll/{poll_id}', 'UserController@deletePoll')->middleware('getUserId');
