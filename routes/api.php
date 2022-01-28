<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\Finances\FinancesController;
use App\Http\Controllers\Tasks\TaskListController;

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

Route::prefix('finances')->group(function() {
    Route::get('/', [FinancesController::class, 'index']);
    Route::get('{finance}', [FinancesController::class, 'show']);
});

Route::prefix('tasks')->group(function() {
    Route::get('/', [TaskListController::class, 'index']);
    Route::get('{task}', [TaskListController::class, 'show']);
    Route::post('list/store', [TaskListController::class, 'store']);
});

Route::prefix('auth')->middleware('api')->group(function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});
