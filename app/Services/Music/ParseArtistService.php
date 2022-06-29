<?php

namespace App\Services\Music;

class ParseArtistService
{
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
                                if ($info['extension'] === 'mp3') {
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
     * @return mixed|string
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
    public function collectData($folder): mixed
    {
        $folders = $this->parseFolder($folder);

        try {
            $this->validateAlbums($folders);
        } catch (\Exception $exception) {
            return ['success' => 'false', 'message' => $exception->getMessage()];
        }

        $artistName = $this->getFolderName($folder);

        $result = ['artist' => $artistName];
        $result['albums'] = [];

        foreach ($folders as $albumKey => $item) {
            preg_match_all('/([0-9]{4}) - (.*)/i', $item, $match);
            $albumParts = array_column($match, 0);

            $albumFolder = $folder . $item;
            $rawTracks = $this->parseFolder($albumFolder, 'tracks');

            $tracks = [];

            foreach ($rawTracks as $trackKey => $track) {
                try {
                    $match = $this->parseTrack($track);
                } catch (\Exception $exception) {
                    return ['success' => 'false', 'message' => $exception->getMessage()];
                }

                $trackParts = array_column($match, 0);

                $tracks[$trackKey]['number'] = $trackParts[1];
                $tracks[$trackKey]['name'] = rtrim($trackParts[2], '.mp3');
                $tracks[$trackKey]['path'] = $folder . '\\' . $track;
            }

            $cover = $rawTracks['cover'] ?: null;
            unset($tracks['cover']);

            array_push($result['albums'], [
                'year' => $albumParts[1],
                'name' => $albumParts[2],
                'cover' => $cover,
                'tracks' => $tracks
            ]);
        }

        return $result;
    }
}
