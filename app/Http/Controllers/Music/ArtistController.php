<?php

namespace App\Http\Controllers\Music;

use App\Http\Requests\Music\Artist\IndexRequest;
use App\Http\Requests\Music\Artist\StoreRequest;
use App\Http\Controllers\Controller;
use App\Models\Music\Artist;
use App\Http\Resources\Music\ArtistResource;
use App\Http\Resources\Music\ArtistCollection;

class ArtistController extends Controller
{
    protected $artists;

    public function __construct(Artist $artists)
    {
        $this->artists = $artists;
    }

    public function index(IndexRequest $request): ArtistCollection
    {
        return new ArtistCollection($this->artists->getitems());
    }

    public function store(StoreRequest $request)
    {
        $artist = Artist::create($request->validated());

        return $this->artistResponse($artist);
    }

    protected function artistResponse(Artist $artist): ArtistResource
    {
        return new ArtistResource($artist);
    }
}
