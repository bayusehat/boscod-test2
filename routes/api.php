<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('/register','App\Http\Controllers\Api\AuthController@register');
Route::post('/login','App\Http\Controllers\Api\AuthController@login');
Route::post('/update-token','App\Http\Controllers\Api\AuthController@refresh');

Route::middleware(['auth:api'])->group(function () {
    Route::post('/transfer','App\Http\Controllers\Api\TransaksiController@createTransfer');
    Route::get('/bank','App\Http\Controllers\Api\TransaksiController@listBank');
});