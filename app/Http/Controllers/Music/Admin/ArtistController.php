<?php

namespace App\Http\Controllers\Music\Admin;

use App\Helpers\ImageUpload;
use App\Http\Controllers\Controller;
use App\Http\Requests\Music\Artist\IndexRequest;
use App\Http\Requests\Music\Artist\StoreRequest;
use App\Http\Requests\Music\Artist\UpdateRequest;
use App\Http\Resources\Music\Artists\AdminArtistCollection;
use App\Http\Resources\Music\Artists\AdminArtistResource;
use App\Models\Music\Artist;

class ArtistController extends Controller
{
    protected $artists;

    public function __construct(Artist $artists)
    {
        $this->artists = $artists;
    }

    public function index(IndexRequest $request) {
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

        $message = 'Исполнитель ' . $artist->name . ' успешно добавлен!';

        return $this->artistResponse($artist, $message);
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

            if (is_array($requestData['tags'])) {
                $artist->tags()->sync($requestData['tags']);
            }

            $message = 'Исполнитель ' . $artist->name . ' успешно обновлён!';

            return $this->artistResponse($artist, $message);
        } else {
            return [
                'success' => false,
                'errors' => [
                    'Ошибка редактирования исполнителя!'
                ]
            ];
        }
    }

    protected function artistResponse(Artist $artist, string $message = ''): array
    {
        $resource = new AdminArtistResource($artist);

        return [
            'success' => true,
            'message' => $message,
            'data' => $resource
        ];
    }

    protected function artistsResponse($artists): array
    {
        $resources = new AdminArtistCollection($artists);

        return ['success' => true, 'data' => $resources];
    }
}
