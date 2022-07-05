<?php

namespace App\Http\Controllers\Music;

use App\Http\Requests\Music\Artist\IndexRequest;
use App\Http\Requests\Music\Artist\StoreRequest;
use App\Http\Requests\Music\Artist\AlbumsRequest;
use App\Http\Controllers\Controller;
use App\Models\Music\Artist;
use App\Http\Resources\Music\ArtistResource;
use App\Http\Resources\Music\ArtistCollection;
use App\Services\UploadImageService;

class ArtistController extends Controller
{
    protected $artists;

    public function __construct(Artist $artists, UploadImageService $uploadImageService)
    {
        $this->artists = $artists;
        $this->uploadImageService = $uploadImageService;
    }

    public function index(IndexRequest $request): ArtistResource|ArtistCollection
    {
        if(empty($request->validated())) {
            return new ArtistCollection($this->artists->getitems());
        } else {
            return $this->artistResponse(Artist::find($request->validated()['id']));
        }
    }

    public function store(StoreRequest $request)
    {
        $imagePath = $this->uploadImageService->upload($request->image, $request->name);

        $artist = Artist::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'content' => $request->content,
            'image' => $imagePath
        ]);

        return $this->artistResponse($artist);
    }

    public function artist(AlbumsRequest $request)
    {
        dd($request->validated());
    }

    protected function artistResponse(Artist $artist): ArtistResource
    {
        return new ArtistResource($artist);
    }
}
