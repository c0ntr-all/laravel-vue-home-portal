<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Http\Requests\Music\Playlist\IndexRequest;
use App\Http\Resources\Music\Playlists\PlaylistResource;
use App\Models\Music\Playlist;
use App\Http\Resources\Music\Playlists\PlaylistsCollection;

class PlaylistController extends Controller
{
    public function index(IndexRequest $request, Playlist $playlist): PlaylistResource
    {
        return new PlaylistResource($playlist);
    }

    public function getItems(): PlaylistsCollection
    {
        $playlists = Playlist::user(auth()->user()->id)->get();

        return new PlaylistsCollection($playlists);
    }
}
