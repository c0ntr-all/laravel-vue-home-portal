<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Music\Playlist\GetItemsRequest;
use App\Http\Requests\Music\Playlist\StoreRequest;
use App\Http\Resources\Music\Playlists\PlaylistResource;
use App\Models\Music\Playlist;
use App\Http\Resources\Music\Playlists\PlaylistsCollection;
use App\Repositories\PlaylistRepository;

class PlaylistController extends BaseController
{
    public function __construct(private PlaylistRepository $repository)
    {
    }

    public function index(GetItemsRequest $request): PlaylistsCollection
    {
        $out = $this->repository->getPlaylists($request->validated());

        return new PlaylistsCollection($out);
    }

    public function show(Playlist $playlist): PlaylistResource
    {
        return new PlaylistResource($playlist);
    }

    public function store(StoreRequest $request)
    {
        $out = $this->repository->store($request->validated());

        return $this->sendResponse($out, 'Playlist created successfully!');
    }

}
