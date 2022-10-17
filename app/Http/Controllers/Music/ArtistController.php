<?php

namespace App\Http\Controllers\Music;

use App\Http\Requests\Music\Artist\IndexRequest;
use App\Http\Requests\Music\Artist\FilterRequest;
use App\Http\Requests\Music\Artist\StoreRequest;
use App\Http\Requests\Music\Artist\UpdateRequest;
use App\Http\Controllers\Controller;
use App\Models\Music\Artist;
use App\Http\Resources\Music\ArtistResource;
use App\Http\Resources\Music\ArtistCollection;
use App\Helpers\ImageUpload;

class ArtistController extends Controller
{
    protected $artists;
    protected $uploadImageService;

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

        return new ArtistCollection(Artist::getFiltered($filters));
    }

    public function store(StoreRequest $request)
    {
        $imagePath = ImageUpload::make()
                                ->setDiskName('public')
                                ->setFolder('music/artists/posters')
                                ->setSourceName($request->name)
                                ->upload($request->image);

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
        $update = [];

        $artist = Artist::find($request->validated()['id']);

        if ($artist) {
            $update = array_merge($update, $request->validated());

            if (isset($request->validated()['image'])) {
                $update['image'] = ImageUpload::make()
                                              ->setDiskName('public')
                                              ->setFolder('music/artists/posters')
                                              ->setSourceName($request->validated()['name'])
                                              ->upload($request->validated()['image']);
            }

            $artist->update($update);

            if (isset($request->validated()['tags'])) {
                $tags = $request->validated()['tags'];
                $arrTags = explode(',', $tags);

                $artist->tags()->sync($arrTags);
            }

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
