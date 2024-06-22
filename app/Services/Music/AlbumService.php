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
        $dto->image = $this->saveCover($dto->image, $dto->name, $artist->name);

        $album = $this->updateOrCreateAlbum($artist, $dto->toArray());

        return $album;
    }

    /**
     * @param Artist $artist
     * @param array $albumData
     * @return Album
     */
    private function updateOrCreateAlbum(Artist $artist, array $albumData): Album
    {
        /** @var Album $album */
        $album = $artist->albums()->updateOrCreate([
            'name' => $albumData['name'],
            'cd' => $albumData['cd'],
        ], [
            'path' => $albumData['path'],
            'image' => $albumData['image'],
            'date' => $albumData['date'],
            'cd' => $albumData['cd']
        ]);

        return $album;
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
