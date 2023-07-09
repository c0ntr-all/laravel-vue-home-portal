<?php

namespace App\Repositories;

use App\Models\Music\Playlist;

class PlaylistRepository
{
    public function getPlaylists()
    {
        return auth()->user()->playlists()->get();
    }

    public function store($requestData)
    {
        return auth()->user()->playlists()->create($requestData);
    }
}
