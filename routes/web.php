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

    $mp3 = new Acekyd\LaravelMP3\LaravelMP3();
    $artist = $mp3->getArtist($path); // Получить Исполнителя
    $album = $mp3->getAlbum($path); // Получить Альбом
    $year = $mp3->getYear($path); // Получить год
    $genre = $mp3->getGenre($path); // Получить жанр
    $title = $mp3->getTitle($path); // Получить название трека
    $trackNo = $mp3->getTrackNo($path); // Получить номер трека
    $bitrate = $mp3->getBitrate($path); // Получить битрейт
    $duration = $mp3->getDuration($path); // Получить длительность трека
    $format = $mp3->getFormat($path); // Получить формат трека
    $mime = $mp3->getMime($path); // Получить MIME тип файла
    $isLossless = $mp3->isLossless($path); // Ялвяется ли lossless

    dd($artist, $album, $year, $genre, $title, $trackNo, $bitrate, $duration, $format, $mime, $isLossless);

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
