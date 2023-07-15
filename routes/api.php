<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\Finances\FinancesController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\Music\Admin\ArtistController as AdminArtistController;
use App\Http\Controllers\Music\AlbumController;
use App\Http\Controllers\Music\ArtistController;
use App\Http\Controllers\Music\MusicHistoryController;
use App\Http\Controllers\Music\PlaylistController;
use App\Http\Controllers\Music\TagController;
use App\Http\Controllers\Music\TrackController;
use App\Http\Controllers\Music\UploadController;
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
                Route::prefix('artists')->group(function() {
                    Route::post('get', [AdminArtistController::class, 'getArtists']);
                    Route::post('store', [AdminArtistController::class, 'store']);
                    Route::post('update', [AdminArtistController::class, 'update']);
                    Route::post('search', [SearchController::class, 'search']);
                });
                Route::post('/', [ArtistController::class, 'index']);
                Route::post('get', [ArtistController::class, 'getArtists']);
            });
            Route::post('upload', [UploadController::class, 'upload']);
            Route::prefix('artists')->group(function() {
                Route::post('/', [ArtistController::class, 'index']);
                Route::post('get', [ArtistController::class, 'getArtists']);
                Route::post('store', [ArtistController::class, 'store']);
                Route::post('update', [ArtistController::class, 'update']);
            });
            Route::post('albums', [AlbumController::class, 'index']);
            Route::prefix('tracks')->group(function() {
                Route::post('/', [TrackController::class, 'getItems']);
                Route::post('{track}/play', [TrackController::class, 'play']);
                Route::post('{track}/rate', [TrackController::class, 'rate']);
                Route::patch('{track}/playlists/update', [TrackController::class, 'updatePlaylists']);
                Route::post('{track}/playlists/delete', [TrackController::class, 'deleteFromPlaylist']);
            });
            Route::prefix('tags')->group(function() {
                Route::post('/', [TagController::class, 'index']);
                Route::put('store', [TagController::class, 'store']);
                Route::patch('{tag}/update', [TagController::class, 'update']);
                Route::post('{tag}/delete', [TagController::class, 'delete']);
                Route::post('select', [TagController::class, 'tagsSelect']);
                Route::post('tree', [TagController::class, 'tagsTree']);
            });
            Route::prefix('playlists')->group(function() {
                // Trying to set "playlists" route as default for list of playlists for better view while requesting.
                Route::post('/', [PlaylistController::class, 'getItems']);
                Route::get('{playlist}/index', [PlaylistController::class, 'index']);
                Route::put('store', [PlaylistController::class, 'store']);
            });
            Route::prefix('history')->group(function() {
                Route::post('/', [MusicHistoryController::class, 'getItems']);
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
