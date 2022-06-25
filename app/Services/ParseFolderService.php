<?php

namespace App\Services;

class ParseFolderService
{
    /**
     * Проверяет являются ли переданные каталоги музыкальными альбомами формата 2019 - AlbumName
     *
     * @param $folders
     */
    public function validateAlbums($folders)
    {
        foreach($folders as $folder) {
            if(!preg_match('/[0-9]{4} - .*/i', $folder)) {
                throw new \Exception('В каталоге присутствует папка неверного вормата: ' . $folder);
            }
        }

        return true;
    }

    /**
     * Возвращает все вложенные каталоги конкретного каталога
     *
     * @param $folder
     * @return array
     */
    public function getFolders($folder): array
    {
        if(!empty($folder)) {
            $dirElements = scandir($folder);
            $dirs = [];

            foreach($dirElements as $dirItem) {
                if($dirItem != '..' && $dirItem != '.' && is_dir($folder . $dirItem)) {
                    $dirs[] = $dirItem;
                }
            }

            return $dirs;
        }
    }


    /**
     * Возвращает имя выбранного каталога из пути
     *
     * @param $folder
     * @return mixed|string
     */
    public function getFolderName($folder): string
    {
        $foldersPath = explode('\\', rtrim($folder, '\\'));

        return array_pop($foldersPath);
    }


    /**
     * @param $folder
     * @return mixed
     */
    public function parseFolder($folder): mixed
    {
        $folders = $this->getFolders($folder);

        try {
            $this->validateAlbums($folders);
        }catch(\Exception $exception) {
            return $exception->getMessage();
        }

        $artistName = $this->getFolderName($folder);

        $result = [];
        foreach($folders as $folder) {
            preg_match_all('/([0-9]{4}) - (.*)/i', $folder, $match);
            $albumParts = array_column($match, 0);
            $year = $albumParts[1];
            $albumName = $albumParts[2];


        }

        return $result;
    }
}
