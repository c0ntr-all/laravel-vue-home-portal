<?php declare(strict_types=1);

namespace App\Services\Music\Parse;

use Exception;

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

        $result = ['artists' => []];

        foreach ($library as $artist) {
            $artistData = [
                'name' => $artist['name'],
                'albums' => []
            ];

            foreach ($artist['albums'] as $album) {
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
            'image' => $this->parseFolderService->getAlbumCover($albumPath),
            'date' => $track['date'],
            'is_date_verified' => false,
            'path' => $albumPath
        ];
    }
}
