<?php

namespace App\Http\Controllers\Music;

use Illuminate\Http\Response;
use App\Http\Controllers\BaseController;
use App\Models\Music\Album;
use App\Http\Resources\Music\AlbumResource;

class AlbumController extends BaseController
{
    public function show(Album $album): array|Response
    {
        $album->load('tracks');

        return $this->sendResponse(new AlbumResource($album), 'Album loaded successfully!');
    }
}
