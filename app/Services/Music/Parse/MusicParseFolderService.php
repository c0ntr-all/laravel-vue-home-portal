<?php declare(strict_types=1);

namespace App\Services\Music\Parse;

class MusicParseFolderService
{
    protected const string DEFAULT_COVER_NAME = 'Cover.jpg';
    protected const array TRACK_EXTENSIONS = ['mp3'];

    public function process(string $path): array
    {
        return $this->getTracks($path);
    }

    /**
     * Проверяет круглые скобки в имени альбома, есть ли там тип альбома, например, Single или EP
     *
     * @param $string
     * @return array
     */
    public function getAlbumAttributes($string): array
    {
        preg_match_all('/\((.+?)\)/', $string, $matches);

        return $matches;
    }

    /**
     * Возвращает путь до обложки в папке, если оно есть
     *
     * @param string $albumPath
     * @return string|null
     */
    public function getAlbumCover(string $albumPath): ?string
    {
        $defaultCover = $albumPath . DIRECTORY_SEPARATOR . static::DEFAULT_COVER_NAME;

        return file_exists($defaultCover) ? $defaultCover : null;
    }

    /**
     * Ищет треки заданных форматов в папках и подпапках указанного пути
     *
     * @param string $path
     * @return array
     */
    private function getTracks(string $path): array
    {
        $dirCanonical = realpath($path);

        static $items = [];

        if ($dirStream = opendir($dirCanonical)) {
            while (false !== ($fileName = readdir($dirStream))) {
                if ($fileName == "." || $fileName == "..") {
                    continue;
                }

                // На всякий случай, игнорим папку FLAC
                if ($fileName == 'FLAC') {
                    continue;
                }

                $dirItem = $dirCanonical . DIRECTORY_SEPARATOR . $fileName;

                if (is_dir($dirItem)) {
                    $this->getTracks($dirItem);
                }

                $fileInfo = pathinfo($fileName);

                if (isset($fileInfo['extension']) && in_array($fileInfo['extension'], self::TRACK_EXTENSIONS)) {
                    $items[] = $dirItem;
                }
            }
        }

        return $items;
    }
}
