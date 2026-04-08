<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\DiscussionsController;
use App\Http\Controllers\FoldersController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\GroupUsersController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\SubscriptionPlansController;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\TanzabooksController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnnotationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::options('{any}', function () {
    return response()->json([], 200);
})->where('any', '.*');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function (){
    Route::post('user-signup', 'register');
    Route::post('user-login', 'login');
    Route::post('create-password', 'changePassword');
    Route::post('verify-mobile-otp', 'verifyOtp');
    Route::post('forget-password', 'forgetPassword');
});

Route::get('sample-folder/{folder_id}', [TanzabooksController::class, 'sample_folder_show']);
Route::get('sample-folders', [TanzabooksController::class, 'sample_folder']);
Route::resource('tanzabook', TanzabooksController::class)->only('show');

Route::group(['middleware' => 'auth:sanctum'], function (){

    Route::group(['middleware' => ['auth:sanctum', 'isActivePlan']], function () {

        Route::get('user-logout', [AuthController::class, 'logout']);

        Route::controller(DashBoardController::class)->group(function () {
            Route::get('dashboard', 'index');
        });

        Route::controller(UserController::class)->group(function () {
            Route::get('user/view', 'view');
            Route::get('user', 'search');
            Route::get('user/folders', 'folders');
            Route::get('user/groups', 'groups');
            Route::patch('user', 'update');
            Route::post('user/password', 'updatePassword');
        });

        Route::controller(TanzabooksController::class)->group(function () {
            Route::get('tanzabooks/shared', 'sharedWithMe');
            Route::post('tanzabook/share', 'share');
            Route::post('tanzabook/move', 'move');
            Route::get('tanzabook/discussion/{tanzabook}', 'discussions');
        });

        Route::resource('annotation', AnnotationsController::class)->only('store', 'show', 'destroy');
        Route::post('annotation/comment', [DiscussionsController::class, 'storeAnnotationComment']);
        Route::get('annotation/comment/{annotation}', [AnnotationsController::class, 'comments']);

        Route::resource('discussion', DiscussionsController::class)->only('store', 'index', 'destroy');
        Route::resource('folder', FoldersController::class)->only('store', 'show', 'destroy', 'update');
        Route::resource('group', GroupsController::class)->only('store', 'show', 'update', 'destroy');
        Route::resource('group-member', GroupUsersController::class)->only('store');
        Route::resource('tanzabook', TanzabooksController::class)->only('store', 'destroy', 'update');
        Route::resource('upload', UploadController::class)->only('store');
        Route::resource('subscription', SubscriptionsController::class)->only('store');

    });

    Route::resource('plans', SubscriptionPlansController::class)->only('index');

    Route::controller(PaymentsController::class)->prefix('payment')->group(function () {
        Route::post('create-order', 'razorPayOrderGenerate');
        Route::post('verify-payment', 'verifyPayment');
    });

    Route::get('plans-delete', [SubscriptionsController::class, 'deleteActiveSubscription']);

});

Route::get('/test', function () {
    return response()->json(['message' => 'API working']);
});
