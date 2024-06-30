<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Client\Music\Albums\Page\AlbumResource;
use App\Http\Resources\Client\Music\Artists\Page\Albums\AlbumCollection;
use App\Models\Music\Album;
use App\Models\Music\Artist;
use Illuminate\Http\Response;

class AlbumController extends BaseController
{
    public function show(Album $album): array|Response
    {
        $album->load(['tracks.artists', 'tags']);

        return $this->sendResponse(new AlbumResource($album), 'Album loaded successfully!');
    }

    public function getAlbumsByArtist(Artist $artist): array|Response
    {
        return $this->sendResponse(new AlbumCollection($artist->albums->whereNull('parent_id')), 'Albums loaded successfully!');
    }
}
