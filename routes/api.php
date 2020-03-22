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

Route::get('/', function () {
    return response()->json(['status' => 200, 'message' => 'Use the API!']);
});

Route::middleware('auth:api')->group(function() {
    Route::get('/me', UserController::class . '@me');
    Route::post('/riskgroups/add', '\App\Http\Controllers\RiskGroupController@add');
});

Route::post('/user/register', '\App\Http\Controllers\UserController@register');
Route::post('/auth/login', '\App\Http\Controllers\AuthController@login')->name('login');

Route::get('/riskgroups', '\App\Http\Controllers\RiskGroupController@list');
