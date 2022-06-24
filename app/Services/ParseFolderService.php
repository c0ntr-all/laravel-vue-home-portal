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
                throw new \Exception('В каталоге присутствуют папки неверного вормата: ' . $folder);
            }
        }

        return true;
    }

    /**
     * Получает все все вложенные каталоги конкретного каталога
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

        $result = [];
        foreach($folders as $folder) {
            $result[] = $folder;
        }

        return $result;
    }
}
