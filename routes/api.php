<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\Admin\CategoryController;
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

Route::group(['prefix' => '/v1', 'middleware' => ['json.response']], function () {

    Route::group(['prefix' => '/admin', 'middleware' => ['json.response', 'auth:api']], function () {
        Route::resource('caetgories', CategoryController::class);
    });


    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/token', function () {
        $user = \App\Models\User::first();
        return ['accessToken' => $user->createToken('accessToken')->accessToken];
    });

    Route::group(['prefix' => '/search'], function () {
        Route::get('/', [SearchController::class, 'index']);
        Route::get('/suggestion', [SearchController::class, 'suggestion']);
        Route::post('/create-domain', [SearchController::class, 'createDomain']);
    });

    Route::group(['prefix' => '/companies'], function () {
        Route::get('/{domain}', [CompanyController::class, 'index']);
        Route::post('/claim', [CompanyController::class, 'claim']);
    });

    Route::group(['prefix' => '/evaluate'], function () {
        Route::post('/{domain}', [ReviewController::class, 'store']);
    });

    Route::group(['prefix' => '/reviews'], function () {
        Route::get('/recent', [ReviewController::class, 'recent']);
    });





});
