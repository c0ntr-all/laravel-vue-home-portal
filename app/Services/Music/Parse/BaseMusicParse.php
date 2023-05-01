<?php

namespace App\Services\Music\Parse;

use App\Helpers\ImageUpload;
use App\Models\Music\Artist;
use getID3;
use Illuminate\Http\File;

abstract class BaseMusicParse
{
    protected const NOIMAGE = 'no-image.gif';
    protected const EXTENSIONS = ['mp3'];

    public function __construct(private getID3 $getID3)
    {

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
    protected function formatDuration(string $duration)
    {
        $durationParts = explode(':', $duration);

        if (count($durationParts) == 2) {
            $duration = '00:' . $durationParts[0] . ':' . $durationParts[1];
        }

        return $duration;
    }

    /**
     * Возвращает путь до обложки альбома. Либо Cover.jpg, либо иное изображение с расширением jpg,jpeg,png, либо NUll
     *
     * @param string $albumPath
     * @return string|null
     */
    protected function getCoverFolderPath(string $albumPath): string|null
    {
        $defaultCover = $albumPath . DIRECTORY_SEPARATOR . 'Cover.jpg';

        if (file_exists($defaultCover)) {
            return $defaultCover;
        } else {
            $albumItems = scandir($albumPath);

            foreach ($albumItems as $item) {
                $info = pathinfo($item);

                if (in_array($info['extension'], ['jpg', 'png', 'jpeg'])) {
                    return $item;
                }
            }
        }

        return null;
    }
}
