<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\Finances\FinancesController;
use App\Http\Controllers\Tasks\TaskListController;
use App\Http\Controllers\Tasks\TaskController;
use App\Http\Controllers\Reminds\RemindController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\CommentController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->middleware('api')->group(function($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

    Route::middleware('jwt.auth')->group(function() {
        Route::prefix('finances')->group(function() {
            Route::get('/', [FinancesController::class, 'index']);
            Route::get('{finance}', [FinancesController::class, 'show']);
        });

        Route::prefix('tasks')->group(function() {
            Route::get('/', [TaskListController::class, 'index']);
            Route::put('list/store', [TaskListController::class, 'store']);
            Route::post('list/{list}/update', [TaskListController::class, 'update']);
            Route::delete('list/{list}/delete', [TaskListController::class, 'delete']);
            Route::post('store/{taskList}', [TaskController::class, 'store']);
            Route::patch('{task}/update', [TaskController::class, 'update']);
            Route::delete('{task}/delete', [TaskController::class, 'delete']);

            Route::post('comments/store', [CommentController::class], 'store');
            Route::post('comments/delete', [CommentController::class], 'delete');
        });

        Route::prefix('reminds')->group(function() {
            Route::get('/', [RemindController::class, 'index']);
            Route::post('store', [RemindController::class, 'store']);
            Route::post('/{remind}/update', [RemindController::class, 'update']);
        });

        Route::prefix('restaurants')->group(function() {
            Route::get('/', [RestaurantController::class, 'index']);
            Route::post('store', [RestaurantController::class, 'store']);
            Route::patch('update', [RestaurantController::class, 'update']);
        });
    });
});
