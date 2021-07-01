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
        Route::resource('categories', CategoryController::class);
        Route::resource('companies', \App\Http\Controllers\Api\Admin\CompanyController::class);
        Route::resource('reviews', \App\Http\Controllers\Api\Admin\ReviewController::class);
        Route::resource('users', \App\Http\Controllers\Api\Admin\UserController::class);
        Route::resource('mail-templates', \App\Http\Controllers\Api\Admin\TemplateController::class);

        Route::get('settings/mail-settings', [\App\Http\Controllers\Api\Admin\MailController::class, 'index']);
        Route::post('settings/mail-settings', [\App\Http\Controllers\Api\Admin\MailController::class, 'setting']);

        Route::post('upload', [\App\Http\Controllers\Api\Admin\FileController::class, 'store']);
    });

    Route::post('/token', function () {
        $user = \App\Models\User::first();
        return ['accessToken' => $user->createToken('accessToken')->accessToken];
    });

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:api');

    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('/me', [\App\Http\Controllers\Api\UserController::class, 'me']);
        Route::post('/evaluate/{domain}', [ReviewController::class, 'store']);
        Route::group(['prefix' => '/business', 'middleware' => ['business']], function () {
            Route::get('/companies', [\App\Http\Controllers\Api\Business\CompanyController::class, 'index']);
            Route::get('/companies/{domain}', [\App\Http\Controllers\Api\Business\CompanyController::class, 'show']);
            Route::patch('/companies/{domain}', [\App\Http\Controllers\Api\Business\CompanyController::class, 'update']);

            Route::get('/benchmark/{domain}', [\App\Http\Controllers\Api\Business\BenchmarkController::class, 'index']);
            Route::post('/benchmark/{domain}', [\App\Http\Controllers\Api\Business\BenchmarkController::class, 'store']);
            Route::post('/benchmark/{domain}/positions', [\App\Http\Controllers\Api\Business\BenchmarkController::class, 'updatePositions']);
            Route::delete('/benchmark/{domain}/{uuid}', [\App\Http\Controllers\Api\Business\BenchmarkController::class, 'destroy']);

            Route::post('/{domain}/logo', [\App\Http\Controllers\Api\Business\CompanyController::class, 'logo']);

            Route::post('/{domain}/reviews/{uuid}', [\App\Http\Controllers\Api\Business\ReviewReplyController::class, 'store']);
            Route::delete('/{domain}/reviews/{uuid}', [\App\Http\Controllers\Api\Business\ReviewReplyController::class, 'destroy']);

            Route::get('/company-information/{domain}', [\App\Http\Controllers\Api\Business\CompanyInformationController::class, 'show']);
            Route::patch('/company-information/{domain}', [\App\Http\Controllers\Api\Business\CompanyInformationController::class, 'update']);

            Route::get('/{domain}/review-statistics', [\App\Http\Controllers\Api\Business\CompanyController::class, 'reviewStatistics']);
            Route::get('/{domain}/reviews', [\App\Http\Controllers\Api\Business\ReviewController::class, 'index']);

            Route::get('/{domain}/templates', [\App\Http\Controllers\Api\Business\TemplateController::class, 'index']);
            Route::get('/{domain}/templates/{uuid}', [\App\Http\Controllers\Api\Business\TemplateController::class, 'show']);

            Route::get('/{domain}/invitations', [\App\Http\Controllers\Api\Business\InvitationController::class, 'index']);
            Route::post('/{domain}/invitations/email-invitations-bulk', [\App\Http\Controllers\Api\Business\InvitationController::class, 'emailInvitationsBulk']);

            Route::get('/categories', [\App\Http\Controllers\Api\Business\CategoryController::class, 'list']);
            Route::get('/{domain}/categories', [\App\Http\Controllers\Api\Business\CategoryController::class, 'index']);
            Route::post('/{domain}/categories', [\App\Http\Controllers\Api\Business\CategoryController::class, 'store']);
            Route::delete('/{domain}/categories', [\App\Http\Controllers\Api\Business\CategoryController::class, 'delete']);
            Route::put('/{domain}/categories', [\App\Http\Controllers\Api\Business\CategoryController::class, 'setDefault']);
        });
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

        Route::get('/{uuid}/info', [CompanyController::class, 'info']);

        Route::get('/categories/{category}', [\App\Http\Controllers\Api\CategoryController::class, 'getCompanyByCategory']);
    });


    Route::group(['prefix' => '/reviews'], function () {
        Route::get('/recent', [ReviewController::class, 'recent']);
    });

    Route::group(['prefix' => '/categories'], function () {
        Route::get('/', [\App\Http\Controllers\Api\CategoryController::class, 'index']);
        Route::get('/{slug}', [\App\Http\Controllers\Api\CategoryController::class, 'category']);
    });


});
