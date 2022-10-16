<?php

use App\Models\Music\Artist;
use Illuminate\Support\Facades\Route;
use App\Models\Tasks\Task;
use App\Models\Tasks\TaskList;

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

Route::get('/tasks/test2', function() {
    $tasks = TaskList::find(1);
    dd($tasks->getItems()->toArray());

})->name('test2');

Route::get('/test3', function() {
    $filters = [
        'tag' => 'industrial metal',
    ];
    $result = (new Artist)->getFiltered($filters);
    dd($result);
})->name('test2');
