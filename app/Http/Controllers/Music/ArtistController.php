<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Http\Requests\Music\Artist\IndexRequest;
use App\Http\Requests\Music\FilterRequest;
use App\Http\Resources\Music\Artists\ArtistCollection;
use App\Http\Resources\Music\Artists\ArtistResource;
use App\Models\Music\Artist;
use Illuminate\Http\Response;

class ArtistController extends Controller
{
    protected $artists;

    public function __construct(Artist $artists)
    {
        $this->artists = $artists;
    }

    public function index(IndexRequest $request)
    {
        return $this->artistResponse(Artist::find($request->validated()['id']));
    }

    public function getArtists(FilterRequest $request) {
        $filters = $request->validated()['filters'] ?? [];

        return new ArtistCollection(Artist::getWithCursor($filters));
    }

    protected function artistResponse($artist): array|Response
    {
        if ($artist) {
            $resource = new ArtistResource($artist);

            return response(['success' => true, 'artists' => $resource], 200);
        } else {
            return response(['success' => false, 'message' => 'Artist not Found!'], 404);
        }
    }
}
