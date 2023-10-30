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
Route::get('/test1', function() {
    $artist = Artist::find(1);
    dd($artist->tracks()->get()->toArray());
})->name('test1');

Route::get('/test2', function() {
    $path = 'F:\\Music\\Metal\\Death Metal\\Melodic Death Metal\\Modern Melodic Death Metal\\In Flames\\Albums\\1995 - Lunar Strain (Japanese Edition)\\16. Eye Of The Beholder.mp3';

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

Route::get('/sockets-test', function () {
    return view('sockets');
});
Route::get('/sockets-send', function () {
    $localsocket = 'tcp://127.0.0.1:1234';
    $user = 'tester01';
    $message = 'Ебаный рот этого казино!';

    // соединяемся с локальным tcp-сервером
    $instance = stream_socket_client($localsocket);
    fwrite($instance, json_encode(['user' => $user, 'message' => $message])  . "\n");
});
