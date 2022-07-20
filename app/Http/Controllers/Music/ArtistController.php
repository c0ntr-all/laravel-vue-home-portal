<?php

namespace App\Http\Controllers\Music;

use App\Http\Requests\Music\Artist\IndexRequest;
use App\Http\Requests\Music\Artist\StoreRequest;
use App\Http\Requests\Music\Artist\UpdateRequest;
use App\Http\Controllers\Controller;
use App\Models\Music\Artist;
use App\Http\Resources\Music\ArtistResource;
use App\Http\Resources\Music\ArtistCollection;
use App\Services\UploadImageService;

class ArtistController extends Controller
{
    protected $artists;
    protected $uploadImageService;

    public function __construct(Artist $artists, UploadImageService $uploadImageService)
    {
        $this->artists = $artists;
        $this->uploadImageService = $uploadImageService;
    }

    public function index(IndexRequest $request): ArtistResource|ArtistCollection
    {
        if(empty($request->validated())) {
            return new ArtistCollection(Artist::all());
        } else {
            return $this->artistResponse(Artist::find($request->validated()['id']));
        }
    }

    public function store(StoreRequest $request)
    {
        $imagePath = $this->uploadImageService->uploadFromForm($request->image, $request->name, 'music/artists/posters');

        $artist = Artist::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'content' => $request->content,
            'image' => $imagePath
        ]);

        return $this->artistResponse($artist);
    }

    public function update(UpdateRequest $request)
    {
        $artist = Artist::find($request->validated()['id']);

        if ($artist->update($request->validated())) {
            return $this->artistResponse($artist);
        } else {
            return ['success' => false, 'error' => 'Ошибка редактирования исполнителя!'];
        }
    }

    protected function artistResponse(Artist $artist): array
    {
        $resource = new ArtistResource($artist);

        return ['success' => true, 'artists' => $resource];
    }
}
