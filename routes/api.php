<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\DiaryEntryController;
use App\Http\Controllers\UserEntriesController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('register', RegisterController::class)->only(['store']);
Route::apiResource('delete_user', RegisterController::class)->only(['destroy']);
//Route::apiResource('get_users', RegisterController::class)->only(['index']);
Route::apiResource('diary_entries.tags', TagController::class)->only(['index']);//not sure about this syntax
Route::apiResource('diary_entries', DiaryEntryController::class);
Route::apiResource('users.diary_entries', UserEntriesController::class)->only(['index']);

// api/auth/login
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class,'login'])->name('login');
    Route::post('logout', [AuthController::class,'logout']);
    
    /*Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');*/

});