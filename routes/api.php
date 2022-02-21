<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\Finances\FinancesController;
use App\Http\Controllers\Tasks\TaskListController;
use App\Http\Controllers\Tasks\TaskController;
use App\Http\Controllers\Reminds\RemindController;

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
            Route::get('{task}', [TaskListController::class, 'show']);
            Route::get('list/{task}/store', [TaskController::class, 'store']);
            Route::post('list/store', [TaskListController::class, 'store']);
        });

        Route::prefix('Reminds')->group(function() {
            Route::get('/', [RemindController::class, 'index']);
            Route::get('{remind}', [RemindController::class, 'show']);
            Route::post('store', [RemindController::class, 'store']);
        });
    });
});
