<?php

use App\Http\Controllers\NotAuthController;
use App\Http\Controllers\UserController;
use App\Models\User;
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

//Not Auth
Route::post('signup', [NotAuthController::class, 'signup']);
Route::post('login', [NotAuthController::class, 'login']);

Route::group(['prefix' => 'auth', 'middleware' => 'auth:users-api'], function() {
    Route::post('logout', [UserController::class, 'logout']);

});
