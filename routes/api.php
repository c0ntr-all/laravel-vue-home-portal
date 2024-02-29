<?php

use App\Http\Controllers\Admin\Music\ArtistController as AdminArtistController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Finances\FinancesController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\Music\AlbumController;
use App\Http\Controllers\Music\ArtistController;
use App\Http\Controllers\Music\MusicHistoryController;
use App\Http\Controllers\Music\PlaylistController;
use App\Http\Controllers\Music\TagController;
use App\Http\Controllers\Admin\Music\TagController as AdminTagController;
use App\Http\Controllers\Music\TrackController;
use App\Http\Controllers\Reminds\RemindController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Tasks\TaskController;
use App\Http\Controllers\Tasks\TaskListController;
use App\Http\Controllers\Users\AuthController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Users\UserSettingsController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\WidgetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

    Route::prefix('user')->group(function() {
        Route::get('/', [UserController::class, 'index']);
        Route::get('settings', [UserSettingsController::class, 'index']);
        Route::patch('settings/update', [UserSettingsController::class, 'update']);
    });

    Route::middleware('jwt.auth')->group(function() {
        Route::prefix('widgets')->group(function() {
            Route::get('get', [WidgetController::class, 'getWidgets']);
        });
        Route::prefix('finances')->group(function() {
            Route::get('/', [FinancesController::class, 'index']);
            Route::get('{finance}', [FinancesController::class, 'show']);
        });

        Route::prefix('tasks')->group(function() {
            Route::get('/', [TaskListController::class, 'index']);
            Route::put('list/store', [TaskListController::class, 'store']);
            Route::post('list/{list}/update', [TaskListController::class, 'update']);
            Route::delete('list/{list}/delete', [TaskListController::class, 'delete']);
            Route::put('store/{taskList}', [TaskController::class, 'store']);
            Route::patch('{task}/update', [TaskController::class, 'update']);
            Route::delete('{task}/delete', [TaskController::class, 'delete']);
        });

        Route::prefix('reminds')->group(function() {
            Route::get('/', [RemindController::class, 'index']);
            Route::put('store', [RemindController::class, 'store']);
            Route::patch('/{remind}/update', [RemindController::class, 'update']);
        });

        Route::prefix('restaurants')->group(function() {
            Route::get('/', [RestaurantController::class, 'index']);
            Route::post('store', [RestaurantController::class, 'store']);
            Route::patch('update', [RestaurantController::class, 'update']);
        });

        Route::prefix('music')->group(function() {
            Route::prefix('admin')->group(function() {
                Route::post('/', [ArtistController::class, 'index']);
                Route::post('get', [ArtistController::class, 'getArtists']);

                Route::prefix('artists')->group(function() {
                    Route::post('/', [AdminArtistController::class, 'index']);
                    Route::post('store', [AdminArtistController::class, 'store']);
                    Route::post('{artist}/update', [AdminArtistController::class, 'update']);
                    Route::post('search', [SearchController::class, 'search']);
                    Route::post('upload', [AdminArtistController::class, 'upload']);
                });
                Route::prefix('tags')->group(function() {
                    Route::get('/', [AdminTagController::class, 'index']);
                    Route::put('store', [AdminTagController::class, 'store']);
                });
            });
            Route::prefix('artists')->group(function() {
                Route::post('/', [ArtistController::class, 'index']);
                Route::post('{artist}/show', [ArtistController::class, 'show']);
                Route::post('{artist}/tracks', [ArtistController::class, 'tracks']);
                Route::post('store', [ArtistController::class, 'store']);
                Route::post('update', [ArtistController::class, 'update']);
            });
            Route::prefix('albums')->group(function() {
                Route::post('/{album}/show', [AlbumController::class, 'show']);
            });
            Route::prefix('tracks')->group(function() {
                Route::post('/', [TrackController::class, 'index']);
                Route::put('store', [TrackController::class, 'store']);
                Route::post('{track}/play', [TrackController::class, 'play']);
                Route::post('{track}/rate', [TrackController::class, 'rate']);
                Route::patch('{track}/playlists/update', [TrackController::class, 'updatePlaylists']);
                Route::post('{track}/playlists/delete', [TrackController::class, 'deleteFromPlaylist']);
            });
            Route::prefix('tags')->group(function() {
                Route::get('/', [TagController::class, 'index']);
                Route::post('select', [TagController::class, 'select']);
                Route::get('{tag:slug}', [TagController::class, 'show']);
                Route::patch('{tag}', [TagController::class, 'update']);
                Route::delete('{tag}', [TagController::class, 'delete']);
            });
            Route::prefix('playlists')->group(function() {
                // Trying to set "playlists" route as default for list of playlists for better view while requesting.
                Route::post('/', [PlaylistController::class, 'index']);
                Route::get('{playlist}/show', [PlaylistController::class, 'show']);
                Route::put('store', [PlaylistController::class, 'store']);
            });
            Route::prefix('history')->group(function() {
                Route::post('/', [MusicHistoryController::class, 'index']);
                Route::put('scrobble', [MusicHistoryController::class, 'scrobble']);
            });
        });

        Route::prefix('comments')->group(function() {
            Route::post('store', [CommentController::class, 'store']);
        });

        Route::prefix('folders')->group(function() {
            Route::post('/', [FolderController::class, 'index']);
        });
    });
});

Route::post('/video', [VideoController::class, 'index']);
Route::get('music/tracks/{track}/play', [TrackController::class, 'play']);
Route::get('video/play', [VideoController::class, 'play']);
