<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post('/login', 'App\Http\Controllers\AuthController@login');

Route::group(['middleware' => 'auth:api'], function () use ($router) {
    Route::post('/create-report', 'App\Http\Controllers\ReportController@generateReport');
});