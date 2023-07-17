<?php

namespace App\Services\Music;

use App\Filters\Filter;
use App\Helpers\ImageUpload;
use App\Models\Music\Artist;
use App\Repositories\ArtistRepository;
use Illuminate\Support\Str;

class ArtistService {
    public function __construct(private ArtistRepository $artistRepository)
    {
    }

    public function getWithPaginate(array $requestData)
    {
        $filter = $this->prepareFilters($requestData);

        return $this->artistRepository->getWithPaginate($filter);
    }

    public function getWithCursor(array $requestData)
    {
        $filter = $this->prepareFilters($requestData);

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

    private function prepareFilters(array $requestData): ?Filter
    {
        if (array_key_exists('filters', $requestData)) {
            $filters = $this->collectFilterClasses($requestData['filters']);
            return new Filter($filters);
        } else {
            return null;
        }
    }

    private function collectFilterClasses($filters): array
    {
        $filterClasses = [];
        $namespace = 'App\Filters\Classes';

        foreach ($filters as $filter => $value) {
            $filterName = Str::studly($filter) . 'Filter';
            $class = $namespace . '\\' . $filterName;
            if (class_exists($class)) {
                $filterClasses[] = new $class($value);
            }
        }

        return $filterClasses;
    }
}
