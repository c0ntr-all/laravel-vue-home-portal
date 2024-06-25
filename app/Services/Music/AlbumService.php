<?php declare(strict_types=1);

namespace App\Services\Music;

use App\Data\Music\AlbumCreateData;
use App\Helpers\ImageUpload;
use App\Models\Music\Album;
use App\Models\Music\Artist;
use Illuminate\Http\File;

readonly class AlbumService {
    public function __construct(
    )
    {
    }

    /**
     * @param Artist $artist
     * @param AlbumCreateData $dto
     * @return Album
     */
    public function saveAlbum(Artist $artist, AlbumCreateData $dto): Album
    {
        if ($dto->image) {
            $dto->image = $this->saveCover($dto->image, $dto->name, $artist->name);
        }

        return $this->updateOrCreateAlbum($dto);
    }

    /**
     * @param AlbumCreateData $dto
     * @return Album
     */
    private function updateOrCreateAlbum(AlbumCreateData $dto): Album
    {
        return Album::updateOrCreate([
            'name' => $dto->name,
        ], [
            'parent_id' => $dto->parent_id,
            'album_type_id' => $dto->album_type_id,
            'version_type_id' => $dto->version_type_id,
            'description' => $dto->description,
            'date' => $dto->date,
            'is_date_verified' => $dto->is_date_verified,
            'image' => $dto->image,
            'path' => $dto->path
        ]);
    }

    /**
     * Сохранение изображения для альбома
     *
     * @param string $path
     * @param string $name
     * @param string $artistName
     * @return string
     */
    public function saveCover(string $path, string $name, string $artistName): string
    {
        $image = new File($path);

        return ImageUpload::make()
                          ->setDiskName('public')
                          ->setFolder("music/artists/{$artistName}/covers")
                          ->setSourceName($name)
                          ->upload($image);
    }
}
