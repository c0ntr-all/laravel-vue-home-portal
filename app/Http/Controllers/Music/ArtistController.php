<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Music\Artist\IndexRequest;
use App\Http\Requests\Music\Artist\UploadRequest;
use App\Http\Resources\Music\Artists\ArtistCollection;
use App\Http\Resources\Music\Artists\ArtistResource;
use App\Models\Music\Artist;
use App\Services\Music\Parse\ParseMusicTracks;
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
        $artist->load(['albums', 'tags']);

        return $this->sendResponse(new ArtistResource($artist), 'Artist successfully loaded!');
    }

    public function upload(UploadRequest $request)
    {
        return (new ParseMusicTracks())->upload($request->validated()['path']);
    }
}
