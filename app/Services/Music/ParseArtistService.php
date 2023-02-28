<?php

namespace App\Services\Music;

use App\Models\Music\Artist;
use App\Models\Music\Album;
use App\Models\Music\Track;
use App\Helpers\ImageUpload;
use getID3;
use Illuminate\Http\File;

class ParseArtistService
{
    private string $noImage = 'no-image.gif';

    private const EXTENSIONS = ['mp3'];

    private const TYPES = ['lp', 'ep', 'single', 'demo', 'split', 'tribute', 'bootleg', 'live', 'instrumental', 'remaster'];

    public function __construct(private getID3 $getID3)
    {

    }

    /**
     * Проверяет являются ли переданные каталоги музыкальными альбомами формата 2019 - AlbumName
     *
     * @param $folders
     * @return bool
     * @throws \Exception
     */
    private function validateAlbums($folders): bool
    {
        if (empty($folders))
            throw new \Exception('В каталоге отсутствуют папки');

        foreach ($folders as $folder) {
            if (!preg_match('/[0-9]{4} - .*/i', $folder)) {
                throw new \Exception('В каталоге присутствует папка неверного формата: ' . $folder);
            }
        }

        return true;
    }

    /**
     * Проверяет трек на валидность и в случае true возвращает результат разбивки по регулярному выражению
     *
     * @param $track
     * @return mixed
     * @throws \Exception
     */
    private function parseTrack($track): mixed
    {
        preg_match_all('/([0-9]{2}).\s(.*)/i', $track, $match);

        if (empty($match[0]))
            throw new \Exception('Трек неверного формата - ' . $track);

        return $match;
    }

    /**
     * Ищет в каталоге все папки или файлы. При поиске файлов, забирает все MP3 файлы и первую попавшуюся картинку jpg|jpeg|png
     *
     * @param $folder
     * @return array
     */
    private function parseFolder($folder, $mode = 'folders'): array
    {
        if (!empty($folder)) {
            $dirElements = scandir($folder);
            $items = [];

            foreach ($dirElements as $key => $dirItem) {
                if ($dirItem != '..' && $dirItem != '.') {
                    switch ($mode) {
                        case 'folders':
                            if (is_dir($folder . $dirItem)) {
                                $items[] = $dirItem;
                            }
                            break;

                        case 'tracks':
                            if (!is_dir($folder . '\\' . $dirItem)) {
                                $info = pathinfo($folder . '\\' . $dirItem);
                                if (in_array($info['extension'], self::EXTENSIONS)) {
                                    $items[] = $info['basename'];
                                } elseif ($info['extension'] === 'jpg' || $info['extension'] === 'jpeg' || $info['extension'] === 'png') {
                                    $items['cover'] = $folder . '\\' . $info['basename'];
                                }
                            }
                            break;
                    }
                }
            }

            return $items;
        }
    }

    /**
     * Возвращает имя выбранного каталога из пути
     *
     * @param $folder
     * @return string
     */
    private function getFolderName($folder): string
    {
        $foldersPath = explode('\\', rtrim($folder, '\\'));

        return array_pop($foldersPath);
    }

    /**
     * Собирает данные по исполнителю в каталоге и формирует массив для загрузки в БД
     *
     * @param $folder
     * @return mixed
     */
    private function collectData($folder): mixed
    {
        $folder = strpos($folder, '\\', -1) ? $folder : $folder . '\\';

        $folders = $this->parseFolder($folder);
        try {
            $this->validateAlbums($folders);
        } catch (\Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }

        $artistName = $this->getFolderName($folder);

        $result = [
            'success' => true,
            'artist' => $artistName
        ];
        $result['albums'] = [];

        foreach ($folders as $albumKey => $albumName) {
            preg_match_all('/([0-9]{4}) - (.*)/i', $albumName, $match);
            $albumParts = array_column($match, 0);

            $albumFolder = $folder . $albumName;
            $rawTracks = $this->parseFolder($albumFolder, 'tracks');

            $tracks = [];

            foreach ($rawTracks as $trackKey => $track) {
                try {
                    $match = $this->parseTrack($track);
                } catch (\Exception $exception) {
                    return ['success' => false, 'message' => $exception->getMessage()];
                }

                $trackParts = array_column($match, 0);

                $tracks[$trackKey]['number'] = $trackParts[1];
                $tracks[$trackKey]['name'] = pathinfo($trackParts[2], PATHINFO_FILENAME);
                $tracks[$trackKey]['path'] = $albumFolder . '\\' . $track;
            }

            $cover = array_key_exists('cover', $rawTracks) ? $rawTracks['cover'] : null;
            unset($tracks['cover']);

            array_push($result['albums'], [
                'year' => $albumParts[1],
                'name' => $albumParts[2],
                'type' => $this->parseAlbumType($albumName),
                'cover' => $cover,
                'tracks' => $tracks
            ]);
        }

        return $result;
    }

    /**
     * Заносит собранную информацию по папкам в базу данных
     *
     * @param $folder
     */
    public function upload($folder)
    {
        $data = $this->collectData($folder);

        if ($data['success']) {
            $artistModel = Artist::updateOrCreate([
                'name' => $data['artist']
            ], [
                'user_id' => auth()->user()->id,
                'name' => $data['artist'],
                'image' => $this->noImage
            ]);

            $lastAlbumPoster = $this->noImage;

            foreach ($data['albums'] as $album) {
                if (!empty($album['cover'])) {
                    $image = new File($album['cover']);
                    $posterPath = ImageUpload::make()
                                             ->setDiskName('public')
                                             ->setFolder('music/albums/posters')
                                             ->setSourceName($album['name'])
                                             ->upload($image);
                    $lastAlbumPoster = $posterPath;
                } else {
                    $posterPath = $this->noImage;
                }

                $albumModel = $artistModel->albums()->updateOrCreate([
                    'name' => $album['name']
                ], [
                    'user_id' => auth()->user()->id,
                    'type' => $album['type'],
                    'year' => $album['year'],
                    'image' => $posterPath
                ]);

                foreach ($album['tracks'] as $track) {
                    $id3TrackInfo = $this->getID3->analyze($track['path']);

                    $albumModel->tracks()->updateOrCreate([
                        'name' => $track['name']
                    ], [
                        'user_id' => auth()->user()->id,
                        'number' => $track['number'],
                        'path_windows' => $track['path'],
                        'duration' => $id3TrackInfo['playtime_string'],
                        'bitrate' => $id3TrackInfo['audio']['bitrate']
                    ]);
                }
            }

            $artistModel->image = $lastAlbumPoster;
            $artistModel->save();

            return ['success' => true, 'artist' => $data['artist']];
        } else {
            return $data;
        }
    }

    private function parseAlbumType($albumName)
    {
        if (!$this->checkAlbumName($albumName)) {
            if (!$this->checkAlbumCircleBrackets($albumName)) {
                return static::TYPES[0];
            } else {
                return $this->checkAlbumCircleBrackets($albumName);
            }
        } else {
            return $this->checkAlbumName($albumName);
        }
    }

    /**
     * Проверяет непосредственно имя альбома, является ли оно типом, например Demo или Split
     *
     * @param $albumName
     * @return mixed
     */
    private function checkAlbumName($string)
    {
        $nameWords = explode(' ', $string);

        foreach (static::TYPES as $type) {
            if (in_array($type, array_map('strtolower', $nameWords))) {
                return $type;
            }
        }

        return null;
    }

    /**
     * Проверяет круглые скобки в имени альбома, есть ли там тип альбома, например, Single или EP
     *
     * @param $string
     * @return mixed
     */
    private function checkAlbumCircleBrackets($string)
    {
        preg_match_all('/\((.+?)\)/', $string, $matches);

        if (!empty($matches[1])) {
            foreach ($matches[1] as $item) {
                $lower = strtolower($item);
                if (in_array($lower, static::TYPES)) {
                    return $lower;
                }
            }
        }

        return null;
    }
}
