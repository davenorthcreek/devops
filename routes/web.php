<?php

use Illuminate\Support\Facades\Route;

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

#Route::view('/', 'dashboard-blade-view');

Route::view('dashboard', 'dashboard-blade-view')->name('dashboard')->middleware('auth');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Route::group(['middleware' => 'auth'], function () {
    Route::resource('user', 'App\Http\Controllers\UserController');
    Route::post('/user_update/{id}', 'App\Http\Controllers\UserController@update');
    Route::get('/delete_user/{id}', 'App\Http\Controllers\UserController@destroy');
});
