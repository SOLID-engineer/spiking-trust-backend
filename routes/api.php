<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SearchController;
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

Route::group(['prefix' => 'v1', 'middleware' => ['json.response']], function () {

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/token', function () {
        $user = \App\Models\User::first();
        return ['accessToken' => $user->createToken('accessToken')->accessToken];
    });

    Route::group(['prefix' => 'search'], function () {
        Route::get('/', [SearchController::class, 'index']);
        Route::get('/suggestion', [SearchController::class, 'suggestion']);
        Route::post('/create-domain', [SearchController::class, 'createDomain']);
    });

});
