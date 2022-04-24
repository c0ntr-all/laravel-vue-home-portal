<?php

use Illuminate\Support\Facades\Route;
use App\Models\Tasks\Task;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'test'])->name('test');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/*Для тестирования*/
Route::get('/tasks/test', function() {
    $tasks = Task::find(5);
    $taskResource = new \App\Http\Resources\TaskResource($tasks);
    dd($taskResource->toArray([]));

})->name('test');
