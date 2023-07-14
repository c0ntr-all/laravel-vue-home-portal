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
        $filters = $this->collectFilter($requestData);
        
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

    private function collectFilter(array $requestData)
    {
        $filters = [];

        if (array_key_exists('filter', $requestData)) {
            $namespace = 'App\Filters\Classes';
            foreach ($requestData['filter'] as $filter => $value) {
                $filterName = Str::studly($filter) . 'Filter';
                $class = $namespace . '\\' . $filterName;
                if (class_exists($class)) {
                    $filters[] = new ($namespace . '\\' . $filterName)($value);
                }
            }
        }

        return $filters;
    }
}
