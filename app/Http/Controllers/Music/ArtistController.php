<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Music\Artist\IndexRequest;
use App\Http\Resources\Music\Artists\ArtistCollection;
use App\Http\Resources\Music\Artists\ArtistResource;
use App\Models\Music\Artist;
use Illuminate\Http\Response;

class ArtistController extends BaseController
{
    /**
     * @param IndexRequest $request
     * @return ArtistCollection
     */
    public function index(IndexRequest $request): ArtistCollection
    {
        $filters = $request->validated()['filters'] ?? [];

        return new ArtistCollection(Artist::getWithCursor($filters));
    }

    /**
     * @param Artist $artist
     * @return Response
     */
    public function show(Artist $artist): Response
    {
        return $this->sendResponse(new ArtistResource($artist), 'Artist successfully loaded!');
    }
}
