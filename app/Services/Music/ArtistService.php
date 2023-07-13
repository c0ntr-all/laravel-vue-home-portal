<?php

namespace App\Services\Music;

use App\Filters\Classes\SearchFilter;
use App\Filters\Filter;
use App\Helpers\ImageUpload;
use App\Models\Music\Artist;
use App\Repositories\ArtistRepository;

class ArtistService {
    public function __construct(private ArtistRepository $artistRepository)
    {
    }

    public function getWithPaginate(array $requestData)
    {
        $filters = [];
        if (array_key_exists('filter', $requestData)) {
            $filters[] = new SearchFilter($requestData['filter']['search']);
        }
        $filter = new Filter($filters);

        return $this->artistRepository->getWithPaginate($filter);
    }

    public function storeArtist(array $requestData)
    {
        $imagePath = ImageUpload::make()
                                ->setDiskName('public')
                                ->setFolder('music/artists/posters')
                                ->setSourceName($requestData['name'])
                                ->upload($requestData['image']);

        return Artist::create([
            'user_id' => auth()->id(),
            'name' => $requestData['name'],
            'content' => $requestData['content'],
            'image' => $imagePath
        ]);
    }

    public function updateArtist(Artist $artist, array $requestData): array
    {
        if (isset($requestData['image'])) {
            $requestData['image'] = ImageUpload::make()
                                          ->setDiskName('public')
                                          ->setFolder('music/artists/posters')
                                          ->setSourceName($requestData['name'])
                                          ->upload($requestData['image']);
        }

        $artist->update($requestData);

        if (is_array($requestData['tags'])) {
            $artist->tags()->sync($requestData['tags']);
        }

        return $requestData;
    }
}
