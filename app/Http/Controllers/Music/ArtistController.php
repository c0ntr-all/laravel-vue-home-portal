<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Music\Artist\IndexRequest;
use App\Http\Requests\Music\Artist\TracksRequest;
use App\Http\Resources\Music\Artists\ArtistCollection;
use App\Http\Resources\Music\Artists\ArtistResource;
use App\Http\Resources\Music\Tracks\TrackCollection;
use App\Models\Music\Artist;
use App\Services\Music\ArtistService;
use Illuminate\Http\Response;

class ArtistController extends BaseController
{
    public function __construct(
        private ArtistService $service
    )
    {
    }

    /**
     * @param IndexRequest $request
     * @return Response
     */
    public function index(IndexRequest $request): Response
    {
        $out = $this->service->getWithCursor($request->validated());

        return $this->sendResponse(new ArtistCollection($out));
    }

    /**
     * @param Artist $artist
     * @return Response
     */
    public function show(Artist $artist): Response
    {
        $artist->load(['albums', 'tags']);

        return $this->sendResponse(new ArtistResource($artist), 'Artist successfully loaded!');
    }

    /**
     * @param Artist $artist
     * @param TracksRequest $request
     * @return TrackCollection
     */
    public function tracks(Artist $artist, TracksRequest $request): TrackCollection
    {
        $tracks = $this->service->getTracks($artist);

        return new TrackCollection($tracks);
    }
}
