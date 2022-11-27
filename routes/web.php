<?php

use App\Http\Resources\Music\ArtistCollection;
use App\Models\Music\Artist;
use Illuminate\Support\Facades\Route;
use App\Models\Tasks\TaskList;
use Illuminate\Contracts\Support\Jsonable;

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
Route::get('/test/{id}', [App\Http\Controllers\TagController::class, 'test'])->name('test');

Route::get('/test2', function() {
    $path = 'F:\Music\Metal\Cyber metal\Sybreed\2004 - Slave Design\01. Bioactive.mp3';

    $getID3 = new getID3();
    $file = $getID3->analyze($path);

    $artist = $file['id3v2']['comments']['artist']; // Получить Исполнителя
    $album = $file['id3v2']['comments']['album']; // Получить Альбом
    $year = $file['id3v2']['comments']['year']; // Получить год
    $genre = $file['id3v2']['comments']['genre']; // Получить жанр
    $title = $file['id3v2']['comments']['title']; // Получить название трека
    $trackNo = $file['id3v2']['comments']['track_number']; // Получить номер трека
    $bitrate = $file['audio']['bitrate']; // Получить битрейт
    $duration = $file['playtime_string']; // Получить длительность трека
    $format = $file['audio']['dataformat']; // Получить формат трека

    dd($artist, $album, $year, $genre, $title, $trackNo, $bitrate, $duration, $format);

})->name('test2');

Route::get('/test3', function() {
    $filters = [];
//    $resource = Artist::filter($filters, 'tag', 'tags', 'name')
//                      ->when(array_key_exists('offset', $filters), function ($q) use ($filters) {
//                          $q->offset($filters['offset'])->limit($filters['limit']);
//                      })
//                      ->cursorPaginate(5)->links();
//    dd($resource);
    return new ArtistCollection(Artist::cursorPaginate(5));
})->name('test3');
