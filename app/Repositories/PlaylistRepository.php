<?php

namespace App\Repositories;

use App\Models\Music\Playlist;

class PlaylistRepository
{
    public function getPlaylists($requestData)
    {
        return auth()->user()->playlists()->get($requestData);
    }

    public function store($requestData)
    {
        return auth()->user()->playlists()->create($requestData);
    }
}
