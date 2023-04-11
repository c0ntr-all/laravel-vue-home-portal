<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Models\Music\Playlist;
use App\Http\Resources\Music\Playlists\PlaylistsCollection;

class PlaylistController extends Controller
{
    public function getItems(): PlaylistsCollection
    {
        return new PlaylistsCollection(Playlist::user(auth()->user()->id)->get());
    }
}
