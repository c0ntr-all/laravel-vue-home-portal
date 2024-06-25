<?php

namespace App\Services\Music;

use App\Data\Music\ArtistCreateData;
use App\Filters\Filter;
use App\Helpers\ImageUpload;
use App\Models\Music\Artist;
use App\Repositories\ArtistRepository;
use Illuminate\Http\File;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

readonly class ArtistService {
    public function __construct(
        private ArtistRepository $repository
    )
    {
    }

    public function getWithPaginate(array $requestData): LengthAwarePaginator
    {
        $filter = $this->prepareFilters($requestData);

        return $this->repository->getWithPaginate($filter);
    }

    public function getWithCursor(array $requestData): CursorPaginator
    {
        $filter = $this->prepareFilters($requestData);

        return $this->repository->getWithCursor($filter);
    }

    /**
     * Сохранение исполнителя с изображением
     *
     * @param ArtistCreateData $dto
     * @return Artist
     */
    public function saveArtist(ArtistCreateData $dto): Artist
    {
        $dto->image = $this->saveCover($dto->image, $dto->name, $dto->name);

        return $this->updateOrCreateArtist($dto);
    }

    public function updateArtist(Artist $artist, array $data): Artist
    {
        //todo: Удалять предыдущее изображение
        if (isset($data['image'])) {
            $data['image'] = $this->saveCover($data['image'], $artist->name, $artist->name);
        }

        $artist->update($data);

        if (isset($requestData['tags'])) {
            $artist->tags()->sync($requestData['tags']);
        }

        return $artist;
    }


    /**
     * Обновление или создание исполнителя
     *
     * @param ArtistCreateData $dto
     * @return Artist
     */
    private function updateOrCreateArtist(ArtistCreateData $dto): Artist
    {
        return Artist::updateOrCreate([
            'name' => $dto->name,
        ], [
            'user_id' => $dto->user_id,
            'description' => $dto->description,
            'country' => $dto->country,
            'path' => $dto->path,
            'image' => $dto->image,
        ]);
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

    /**
     * @param Artist $artist
     * @return CursorPaginator
     */
    public function getTracks(Artist $artist): CursorPaginator
    {
        return $artist->tracks()->cursorPaginate(50);
    }

    /**
     * Сохранение изображения для исполнителя
     *
     * @param string $path
     * @param string $name
     * @param string $artistName
     * @return string
     */
    protected function saveCover(string $path, string $name, string $artistName): string
    {
        $image = new File($path);

        return ImageUpload::make()
                          ->setDiskName('public')
                          ->setFolder("music/artists/{$artistName}/covers")
                          ->setSourceName($name)
                          ->upload($image);
    }
}
