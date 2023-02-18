<?php

use App\Http\Resources\Music\Artists\ArtistCollection;
use App\Models\Music\Album;
use App\Models\Music\Artist;
use App\Models\Music\Tag;
use Illuminate\Support\Facades\Route;
use Iman\Streamer\VideoStreamer;

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
Route::get('/test/{id}', [\App\Http\Controllers\Music\TagController::class, 'test'])->name('test');

Route::get('/test2', function() {
    $path = 'F:\Music\Разборка\Crystal Castles-Plague-kissvk.com.mp3';

    $getID3 = new getID3();
    $file = $getID3->analyze($path);

    $artist = $file['id3v2']['comments']['artist'] ?? 'none'; // Получить Исполнителя
    $album = $file['id3v2']['comments']['album'] ?? 'none'; // Получить Альбом
    $year = $file['id3v2']['comments']['year'] ?? 'none'; // Получить год
    $genre = $file['id3v2']['comments']['genre'] ?? 'none'; // Получить жанр
    $title = $file['id3v2']['comments']['title'] ?? 'none'; // Получить название трека
    $trackNo = $file['id3v2']['comments']['track_number'] ?? 'none'; // Получить номер трека
    $bitrate = $file['audio']['bitrate'] ?? 'none'; // Получить битрейт
    $duration = $file['playtime_string'] ?? 'none'; // Получить длительность трека
    $format = $file['audio']['dataformat'] ?? 'none'; // Получить формат трека

    dd($artist, $album, $year, $genre, $title, $trackNo, $bitrate, $duration, $format);

})->name('test2');

Route::get('/test3', function() {
    $client = new Predis\Client();
    $client->set('foo', 'bar');
    $value = $client->get('foo');

    dd($value);
})->name('test3');
