<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Http\Requests\Music\Playlist\GetItemsRequest;
use App\Http\Requests\Music\Playlist\IndexRequest;
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
}
