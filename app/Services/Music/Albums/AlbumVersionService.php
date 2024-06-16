<?php declare(strict_types=1);

namespace App\Services\Music\Albums;

use App\Models\Music\Album;
use App\Models\Music\AlbumVersion;

readonly class AlbumVersionService {
    public function __construct()
    {
    }

    /**
     * @param Album $album
     * @param array $data
     * @return AlbumVersion
     */
    public function saveAlbumVersion(Album $album, array $data): AlbumVersion
    {
        /** @var AlbumVersion $version */
        $version = $album->versions()->updateOrCreate([
            'version_type_id' => $data['version_type_id'],
        ], [
            'description' => $data['description'],
            'year' => $data['year'],
        ]);

        return $version;
    }
}
