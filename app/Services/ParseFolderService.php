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
        $result = [];
        foreach($folders as $folder) {
            if(!preg_match('/[0-9]{4} - .*/i', $folder)) {
                //throw exception
            }
        }
        return $result;
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

    public function parseFolder($folder)
    {
        $folders = $this->getFolders($folder);

        return $this->validateAlbums($folders);
    }
}
