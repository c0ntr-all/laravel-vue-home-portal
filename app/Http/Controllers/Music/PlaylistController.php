<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Http\Requests\Music\Playlist\GetItemsRequest;
use App\Http\Requests\Music\Playlist\IndexRequest;
use App\Http\Requests\Music\Playlist\StoreRequest;
use App\Http\Requests\Music\Track\UpdatePlaylistsRequest;
use App\Http\Resources\Music\Playlists\PlaylistResource;
use App\Models\Music\Playlist;
use App\Http\Resources\Music\Playlists\PlaylistsCollection;

class PlaylistController extends Controller
{
    public function index(IndexRequest $request, Playlist $playlist): PlaylistResource
    {
        return new PlaylistResource($playlist);
    }

    public function getItems(GetItemsRequest $request): PlaylistsCollection
    {
        $playlists = Playlist::user(auth()->user()->id)
                             ->when(!empty($request->validated()['with_tracks']), function($query) {
                                return $query->with('tracks');
                             })
                             ->get();

        return new PlaylistsCollection($playlists);
    }

    public function store(StoreRequest $request)
    {
        $playlist = Playlist::create(array_merge($request->validated(), ['user_id' => auth()->user()->id]));

        if ($playlist) {
            return response([
                'success' => true,
                'message' => 'Playlist successfully created!',
                'data' => $playlist
            ], 200);
        } else {
            return response([
                'success' => false,
                'message' => 'Something went wrong during creating playlist!'
            ], 500);
        }
    }

}
