<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Http\Requests\Music\Album\IndexRequest;
use App\Models\Music\Album;
use App\Http\Resources\Music\AlbumResource;

class AlbumController extends Controller
{

    public function index(IndexRequest $request): AlbumResource
    {
        return $this->albumResponse(Album::find($request->validated()['id']));
    }

    public function albumResponse(Album $album): AlbumResource
    {
        return new AlbumResource($album);
    }
}
