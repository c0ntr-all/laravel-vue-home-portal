<?php

namespace App\Services\Music\Parse;

use App\Helpers\ImageUpload;
use getID3;
use Illuminate\Http\File;

abstract class BaseMusicParseService
{
    protected getID3 $getID3;

    protected const NO_IMAGE = 'no-image.gif';
    protected const COVER_EXTENSIONS = ['jpg','jpeg','png','gif'];
    protected const TRACK_EXTENSIONS = ['mp3'];

    public function __construct()
    {
        $this->getID3 = new getID3();
    }

    /**
     * Проверяет круглые скобки в имени альбома, есть ли там тип альбома, например, Single или EP
     *
     * @param $string
     * @return array
     */
    protected function getAlbumAttributes($string): array
    {
        preg_match_all('/\((.+?)\)/', $string, $matches);

        return $matches;
    }

    /**
     * Форматирует длительность трека в 00:05:34, вместо 05:34:00
     *
     * @param string $duration
     * @return string
     */
    protected function formatDuration(string $duration): string
    {
        $durationParts = explode(':', $duration);

        if (count($durationParts) == 2) {
            $duration = '00:' . $durationParts[0] . ':' . $durationParts[1];
        }

        return $duration;
    }

    /**
     * Возвращает путь первого попавшегося изображения в каталоге
     *
     * @param string $path
     * @return string|null
     */
    private function findAnotherImageAsCover(string $path): string|null
    {
        $folderItems = scandir($path);

        $coverPath = null;

        foreach ($folderItems as $item) {
            $info = pathinfo($item);

            if (isset($info['extension']) && in_array($info['extension'], self::COVER_EXTENSIONS)) {
                $coverPath = $path . DIRECTORY_SEPARATOR . $item;
                break;
            }
        }

        return $coverPath;
    }

    /**
     * Возвращает путь файла Cover.jpg в каталоге, либо иное изображение в формате jpg,jpeg,png, либо null
     *
     * @param string $albumPath
     * @return string|null
     */
    protected function getCoverFromFolder(string $albumPath): string|null
    {
        $defaultCover = $albumPath . DIRECTORY_SEPARATOR . 'Cover.jpg';

        return file_exists($defaultCover) ? $defaultCover : $this->findAnotherImageAsCover($albumPath);
    }

    /**
     * Сохраняет изображение на сервер и возвращает полученный путь
     *
     * @param string $path
     * @param string $name
     * @return string
     */
    protected function saveCover(string $path, string $name): string
    {
        $image = new File($path);

        return ImageUpload::make()
                          ->setDiskName('public')
                          ->setFolder('music/albums/posters')
                          ->setSourceName($name)
                          ->upload($image);
    }

    /**
     * Получает изображение по переданному пути. Варианты: Cover,jpg, иное изображение в формате jpg,jpeg,png,gif, no-image.gif
     *
     * @param string $path
     * @return string
     */
    protected function createCover(string $path): string
    {
        $coverOriginalPath = $this->getCoverFromFolder($path);

        if ($coverOriginalPath != NULL) {
            return $this->saveCover($coverOriginalPath, basename($path));
        } else {
            return self::NO_IMAGE;
        }
    }
}
