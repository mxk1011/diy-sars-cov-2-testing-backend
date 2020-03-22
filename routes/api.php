<?php

use App\Http\Controllers\UserController;
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

Route::middleware('auth:api')->group(function() {
    Route::get('/me', UserController::class . '@me');
});

Route::post('/user/register', '\App\Http\Controllers\UserController@register');
Route::post('/auth/login', '\App\Http\Controllers\AuthController@login');

Route::get('/riskgroups', '\App\Http\Controllers\RiskGroupController@list');
