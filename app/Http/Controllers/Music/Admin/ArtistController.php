<?php

namespace App\Http\Controllers\Music\Admin;

use App\Helpers\ImageUpload;
use App\Http\Controllers\Controller;
use App\Http\Requests\Music\Artist\FilterRequest;
use App\Http\Requests\Music\Artist\StoreRequest;
use App\Http\Requests\Music\Artist\UpdateRequest;
use App\Http\Resources\Music\Artists\AdminArtistCollection;
use App\Http\Resources\Music\Artists\ArtistResource;
use App\Models\Music\Artist;

class ArtistController extends Controller
{
    protected $artists;

    public function __construct(Artist $artists)
    {
        $this->artists = $artists;
    }

    public function getArtists(FilterRequest $request) {
        $requestData = $request->validated();

        $filters = $requestData['filters'] ?? [];

        return $this->artistsResponse(Artist::getWithPaginate($filters));
    }

    public function store(StoreRequest $request)
    {
        $requestData = $request->validated();

        $imagePath = ImageUpload::make()
                                ->setDiskName('public')
                                ->setFolder('music/artists/posters')
                                ->setSourceName($requestData['name'])
                                ->upload($requestData['image']);

        $artist = Artist::create([
            'user_id' => auth()->id(),
            'name' => $requestData['name'],
            'content' => $requestData['content'],
            'image' => $imagePath
        ]);

        return $this->artistResponse($artist);
    }

    public function update(UpdateRequest $request)
    {
        $requestData = $request->validated();

        $update = [];

        $artist = Artist::find($requestData['id']);

        if ($artist) {
            $update = array_merge($update, $requestData);

            if (isset($request->validated()['image'])) {
                $update['image'] = ImageUpload::make()
                                              ->setDiskName('public')
                                              ->setFolder('music/artists/posters')
                                              ->setSourceName($requestData['name'])
                                              ->upload($requestData['image']);
            }

            $artist->update($update);

            if (!empty($requestData['commonTags'])) {
                $tags = explode(',', $requestData['commonTags']);

                if (!empty($requestData['secondaryTags'])) {
                    $tags = array_merge($tags, explode(',', $requestData['secondaryTags']));
                }

                $artist->tags()->sync($tags);
            }

            return $this->artistResponse($artist);
        } else {
            return ['success' => false, 'error' => 'Ошибка редактирования исполнителя!'];
        }
    }

    protected function artistResponse(Artist $artist): array
    {
        $resource = new ArtistResource($artist);

        return ['success' => true, 'data' => $resource];
    }

    protected function artistsResponse($artists): array
    {
        $resources = new AdminArtistCollection($artists);

        return ['success' => true, 'data' => $resources];
    }
}
