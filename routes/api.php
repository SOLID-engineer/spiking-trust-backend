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

    Route::group(['prefix' => '/admin', 'middleware' => ['auth:api']], function () {
        Route::resource('caetgories', CategoryController::class);
    });

    Route::post('/token', function () {
        $user = \App\Models\User::first();
        return ['accessToken' => $user->createToken('accessToken')->accessToken];
    });

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('/me', [\App\Http\Controllers\Api\UserController::class, 'me']);
        Route::post('/evaluate/{domain}', [ReviewController::class, 'store']);
    });

    Route::post('/login', [\App\Http\Controllers\Api\UserController::class, 'login']);

    Route::group(['prefix' => '/search'], function () {
        Route::get('/', [SearchController::class, 'index']);
        Route::get('/suggestion', [SearchController::class, 'suggestion']);
        Route::post('/create-domain', [SearchController::class, 'createDomain']);
    });

    Route::group(['prefix' => '/companies'], function () {
        Route::post('/claim', [CompanyController::class, 'claim'])->middleware('auth:api');
        Route::post('/accept-company', [CompanyController::class, 'accept'])->middleware('auth:api');
        Route::get('/{domain}', [CompanyController::class, 'index']);
        Route::get('/{domain}/reviews', [CompanyController::class, 'reviews']);
    });

    Route::group(['prefix' => '/evaluate'], function () {
        Route::post('/{domain}', [ReviewController::class, 'store']);
    });

    Route::group(['prefix' => '/reviews'], function () {
        Route::get('/recent', [ReviewController::class, 'recent']);
    });

    Route::group(['prefix' => '/categories'], function () {
        Route::get('/', [\App\Http\Controllers\Api\CategoryController::class, 'index']);
    });



});
