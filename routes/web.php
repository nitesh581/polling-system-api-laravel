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

// List Poll
Route::get('/list_polls', 'UserController@listPolls');

Route::get('/test', 'UserController@test');