<?php declare(strict_types=1);

namespace App\Services\Music\Parse;

use App\Enums\Music\AlbumTypeEnum;
use App\Enums\Music\AlbumVersionTypeEnum;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

readonly class MusicParseService
{
    private string $path;

    public function __construct(
        private MusicParseFolderService $parseFolderService,
        private MusicParseTrackService $parseTrackService,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function process(string $path): array
    {
        $this->path = $path;

        $allTracksPaths = $this->parseFolderService->process($path);

        return $this->makeTracksTree($allTracksPaths);
    }

    /**
     * Получает теги трека по его пути
     *
     * @throws Exception
     */
    private function getTracksInfo(array $allTracks): array
    {
        if (!empty($allTracks)) {
            $total = [];

            foreach($allTracks as $trackPath) {
                $total[] = $this->parseTrackService->getTrackInfo($trackPath);
            }

            return $total;
        } else {
            throw new Exception('This folder doesn\'t contains any tracks!');
        }
    }

    /**
     * Промежуточный вариант дерева для удобного сопоставления исполнителей и альбомов с треками
     *
     * @param array $tracks
     * @return array
     */
    private function buildLibrary(array $tracks): array
    {
        $library = [];

        foreach ($tracks as $track) {
            $artist = $this->getArtistFromTrack($track);
            $album = $this->getAlbumFromTrack($track);

            $artistName = $artist['name'];
            $albumName = $album['name'];

            if (!isset($library[$artistName])) {
                $library[$artistName] = $artist;
            }

            if (!isset($library[$artistName]['albums'][$albumName])) {
                $library[$artistName]['albums'][$albumName] = $album;
            }

            $library[$artistName]['albums'][$albumName]['tracks'][] = $track;
        }

        return $library;
    }

    /**
     * Формирует дерево Artists -> Albums -> Tracks
     *
     * @throws Exception
     */
    private function makeTracksTree(array $tracks): array
    {
        $allTracksInfo = $this->getTracksInfo($tracks);
        $library = $this->buildLibrary($allTracksInfo);

        $albumTypes = Cache::get('album_types');
        $albumVersionTypes = Cache::get('album_version_types');

        $result = ['artists' => []];

        foreach ($library as $artist) {
            $artistData = [
                'name' => $artist['name'],
                'albums' => []
            ];

            foreach ($artist['albums'] as $album) {
                $albumAttributes = $this->parseAlbumCircleBraces($album['name']);

                if (!empty($albumAttributes)) {
                    foreach ($albumAttributes[1] as $attribute) {
                        $attribute = strtolower($attribute);
                        $albumType = $albumTypes->firstWhere(fn ($type) => $type->slug === $attribute);
                        $albumVersionType = $albumVersionTypes->firstWhere(fn ($type) => $type->slug === $attribute);

                        if ($albumType) {
                            $album['album_type_id'] = $albumType->id;
                        }

                        if ($albumVersionType) {
                            $album['version_type_id'] = $albumVersionType->id;
                        }
                    }
                }

                $artistData['albums'][] = $album;
            }

            $result['artists'][] = $artistData;
        }

        return $result;
    }

    /**
     * Подготовка данных для Исполнителя
     *
     * @param array $track
     * @return array
     */
    private function getArtistFromTrack(array $track): array
    {
        return [
            'name' => $track['artist'],
            'path' => $this->path,
            'image' => '',
        ];
    }

    /**
     * Подготовка данных для Альбома
     *
     * @param array $track
     * @return array
     */
    private function getAlbumFromTrack(array $track): array
    {
        $albumPath = dirname($track['path']);

        return [
            'name' => $track['album'],
            'cd' => $track['cd'] ?? 1,
            'album_type_id' => 1,
            'version_type_id' => 1,
            'image' => $this->parseFolderService->getAlbumCover($albumPath),
            'date' => $track['date'],
            'is_date_verified' => false,
            'path' => $albumPath
        ];
    }

    /**
     * Извлекает данные из круглых скобок в имени альбома
     *
     * @param string $albumName
     * @return array|null
     */
    public function parseAlbumCircleBraces(string $albumName): ?array
    {
        if (preg_match_all('/\((.*?)\)/', $albumName, $matches)) {
            return $matches;
        }

        return NULL;
    }

    public function cleanAlbumName(string $albumName): string
    {
        return trim(preg_replace('/\s*\(.*?\)\s*/', '', $albumName));
    }
}
