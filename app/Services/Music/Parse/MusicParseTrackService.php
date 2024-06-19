<?php declare(strict_types=1);

namespace App\Services\Music\Parse;

use getID3;

class MusicParseTrackService
{
    protected getID3 $getID3;

    public function __construct(
    )
    {
        $this->getID3 = new getID3();
    }

    public function getTrackInfo(string $trackPath): array
    {
        $id3TrackInfo = $this->getId3Tags($trackPath);

        if (empty($id3TrackInfo['title'])) {
            throw new \Exception('Track has no title! Path: ' . $trackPath);
        }

        return [
            'artist' => $id3TrackInfo['artist'],
            'album' => $id3TrackInfo['album'],
            'cd' => $id3TrackInfo['cd'] ?? 1,
            'name' => $id3TrackInfo['title'],
            'number' => $id3TrackInfo['track_number'],
            'date' => $this->formatDate($id3TrackInfo['year']),
            'genre' => $id3TrackInfo['genre'],
            'duration' => $this->formatDuration($id3TrackInfo['playtime_string']),
            'bitrate' => $id3TrackInfo['bitrate'],
            'path' => $trackPath
        ];
    }

    /**
     * Получает все небходимые теги из файлов с помощью ID3
     *
     * @param string $path
     * @return array
     */
    private function getId3Tags(string $path): array
    {
        $id3TrackInfo = $this->getID3->analyze($path);

        return [
            'artist' => $id3TrackInfo['id3v2']['comments']['artist'][0] ?? null,
            'album' => $id3TrackInfo['id3v2']['comments']['album'][0] ?? null,
            'title' => $id3TrackInfo['id3v2']['comments']['title'][0] ?? null,
            'genre' => $id3TrackInfo['id3v2']['comments']['genre'][0] ?? null,
            'year' => $id3TrackInfo['id3v2']['comments']['year'][0] ?? null,
            'cd' => $id3TrackInfo['id3v2']['comments']['part_of_a_set'][0] ?? null,
            'playtime_string' => $id3TrackInfo['playtime_string'] ?? null,
            'bitrate' => $id3TrackInfo['audio']['bitrate'] ?? null,
            'track_number' => (int) ($id3TrackInfo['id3v2']['comments']['track_number'][0] ?? 0),
        ];
    }

    /**
     * Преобразует длительность трека в 00:05:34, вместо 05:34:00
     *
     * @param string|null $duration
     * @return string|null
     */
    protected function formatDuration(?string $duration): ?string
    {
        if (!$duration) {
            return null;
        }

        $durationParts = explode(':', $duration);

        if (count($durationParts) == 2) {
            if (count(str_split($durationParts[0])) == 1) {
                $durationParts[0] = '0' . $durationParts[0];
            }
            $duration = '00:' . $durationParts[0] . ':' . $durationParts[1];
        }

        return $duration;
    }

    /**
     * Преобразует дату в формат Y-m-d, если бы был передан год (Y)
     *
     * @param string $dateString
     * @return string|null
     */
    private function formatDate(string $dateString): ?string
    {
        if (preg_match('/^\d{4}$/', $dateString)) {
            return $dateString . '-01-01';
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateString)) {
            return $dateString;
        }

        return NULL;
    }
}
