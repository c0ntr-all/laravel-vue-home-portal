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
        if(empty($folders))
            throw new \Exception('В каталоге отсутствуют папки');

        foreach($folders as $folder) {
            if(!preg_match('/[0-9]{4} - .*/i', $folder)) {
                throw new \Exception('В каталоге присутствует папка неверного формата: ' . $folder);
            }
        }

        return true;
    }

    /**
     * Ищет в каталоге все папки или файлы. При поиске файлов, забирает все MP3 файлы и первую попавшуюся картинку jpg|jpeg|png
     *
     * @param $folder
     * @return array
     */
    private function parseFolder($folder, $mode = 'folders'): array
    {
        if(!empty($folder)) {
            $dirElements = scandir($folder);
            $items = [];

            foreach($dirElements as $key => $dirItem) {
                if($dirItem != '..' && $dirItem != '.') {
                    switch($mode) {
                        case 'folders':
                            if(is_dir($folder . $dirItem)) {
                                $items[] = $dirItem;
                            }
                            break;

                        case 'tracks':
                            $info = pathinfo($folder . '\\' . $dirItem);
                            if($info['extension'] === 'mp3') {
                                preg_match_all('/([0-9]{2}).\s(.*)/i', $info['filename'], $match);
                                $trackParts = array_column($match, 0);
                                $items[$key]['number'] = $trackParts[1];
                                $items[$key]['name'] = $trackParts[2];
                                $items[$key]['path'] = $folder . '\\' . $dirItem;
                            }elseif ($info['extension'] === 'jpg' || $info['extension'] === 'jpeg' || $info['extension'] === 'png') {
                                $items['cover'] = $folder . '\\' . $info['basename'];
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
        }catch(\Exception $exception) {
            return ['success' => 'false', 'message' => $exception->getMessage()];
        }

        $artistName = $this->getFolderName($folder);

        $result = ['artist' => $artistName];
        $result['albums'] = [];

        foreach($folders as $item) {
            preg_match_all('/([0-9]{4}) - (.*)/i', $item, $match);
            $albumParts = array_column($match, 0);

            $albumFolder = $folder . $item;
            $tracks = $this->parseFolder($albumFolder, 'tracks');
            $cover = $tracks['cover'] ?: null;

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
