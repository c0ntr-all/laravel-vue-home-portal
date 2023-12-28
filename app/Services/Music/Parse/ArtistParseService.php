<?php declare(strict_types=1);

namespace App\Services\Music\Parse;

use getID3;
use App\Helpers\ImageUpload;
use App\Models\Music\MusicTag;
use App\Traits\Makeable;
use Illuminate\Http\File;

class ArtistParseService
{
    use Makeable;

    private string $path;
    private array $data;
    protected getID3 $getID3;

    protected const NO_IMAGE = 'no-image.gif';
    protected const DEFAULT_COVER_NAME = 'Cover.jpg';
    protected const COVER_EXTENSIONS = ['jpg','jpeg','png','gif'];
    protected const TRACK_EXTENSIONS = ['mp3'];

    public function __construct(string $path)
    {
        $this->getID3 = new getID3();

        $this->path = $path;
        $this->data = [];
    }

    public function upload(): array
    {
        if (file_exists($this->path)) {
            return $this->parseFolder();
        } else {
            throw new \Exception('The chosen catalog doesn\'t exists!');
        }
    }

    public function parseFolder(): array
    {
        $albums = $this->findAlbums($this->path);

        if (!empty($albums)) {
            foreach ($albums as $albumPath) {
                $cover = $this->getCoverFromFolder($albumPath);

                $albumItems = scandir($albumPath);

                foreach ($albumItems as $key => $item) {
                    if ($item === '.' || $item === '..') {
                        continue;
                    }

                    $info = pathinfo($item);

                    if (isset($info['extension']) && in_array($info['extension'], self::TRACK_EXTENSIONS)) {
                        $trackPath = $albumPath . DIRECTORY_SEPARATOR . $item;
                        $id3TrackInfo = $this->getId3Tags($trackPath);

                        $artistName = $id3TrackInfo['artist'] ?? basename($this->path);
                        $albumName = $id3TrackInfo['album'] ?? basename($albumPath);

                        if (empty($artistName)) {
                            throw new \Exception('Track has no artist info! Path: ' . $trackPath);
                        }

                        if (empty($albumName)) {
                            throw new \Exception('Track has no album info! Path: ' . $trackPath);
                        }

                        if (empty($id3TrackInfo['title'])) {
                            throw new \Exception('Track has no title! Path: ' . $trackPath);
                        }

                        $track = [
                            'name' => $id3TrackInfo['title'],
                            'number' => $id3TrackInfo['track_number'] ?? NULL,
                            'duration' => $this->formatDuration($id3TrackInfo['playtime_string']),
                            'bitrate' => $id3TrackInfo['bitrate'],
                            'path' => $trackPath,
                            'image' => $cover,
                        ];
                        $album = [
                            'name' => $albumName,
                            'image' => $cover,
                            'year' => $id3TrackInfo['year'],
                            'path' => $albumPath,
                        ];
                        $artist = [
                            'name' => $artistName,
                            'image' => $cover,
                            'path' => $this->path,
                        ];
                        if (!in_array($artistName, array_column($this->data, 'name'))) {
                            $this->data[] = $artist;
                        }
                        foreach ($this->data as $artistKey => $artistValue) {
                            if ($artistValue['name'] == $artistName) {
                                if (!isset($this->data[$artistKey]['albums'])) {
                                    $this->data[$artistKey]['albums'] = [];
                                }
                                if (!in_array($albumName, array_column($this->data[$artistKey]['albums'], 'name'))) {
                                    $this->data[$artistKey]['albums'][] = $album;
                                }
                                foreach ($this->data[$artistKey]['albums'] as $albumKey => $albumValue) {
                                    if ($albumValue['name'] == $albumName) {
                                        $this->data[$artistKey]['albums'][$albumKey]['tracks'][] = $track;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $this->data;
    }

    /**
     * Рекурсивно ищет альбомы в папке по маске YYYY - AlbumName.
     * Необходимо для файла с обложкой.
     *
     * @param string $path
     * @return array
     */
    public function findAlbums(string $path): array
    {
        $dirCanonical = realpath($path);

        static $items = [];

        if ($dirStream = opendir($dirCanonical)) {
            while (false !== ($fileName = readdir($dirStream))) {
                if ($fileName == "." || $fileName == ".." || $fileName == 'FLAC') {
                    continue;
                }

                $dirItem = $dirCanonical . DIRECTORY_SEPARATOR . $fileName;

                if (preg_match('/[0-9]{4} - .*/i', basename($fileName))) {
                    $items[] = $dirItem;
                } else if(is_dir($dirItem)) {
                    $this->findAlbums($dirItem);
                }
            }
        }

        return $items;
    }

    private function getTagsIds(array $tagsNames): array
    {
        $ids = [];
        foreach($tagsNames as $name) {
            if ($musicTag = MusicTag::where(['name' => $name])->first()) {
                $ids[] = $musicTag->id;
            }
        }

        return $ids;
    }

    /**
     * Получает все небходимые теги из файлов с помощью ID3
     *
     * @param string $path
     * @return array
     */
    private function getId3Tags(string $path): array
    {
        $out = [];

        $id3TrackInfo = $this->getID3->analyze($path);

        $out['artist'] = array_key_exists('artist', $id3TrackInfo['id3v2']['comments']) ?
            $id3TrackInfo['id3v2']['comments']['artist'][0] :
            null;
        $out['album'] = array_key_exists('album', $id3TrackInfo['id3v2']['comments']) ?
            $id3TrackInfo['id3v2']['comments']['album'][0] :
            null;
        $out['title'] = array_key_exists('title', $id3TrackInfo['id3v2']['comments']) ?
            $id3TrackInfo['id3v2']['comments']['title'][0] :
            null;
        $out['genre'] = array_key_exists('genre', $id3TrackInfo['id3v2']['comments']) ?
            $id3TrackInfo['id3v2']['comments']['genre'][0] :
            null;
        $out['year'] = array_key_exists('year', $id3TrackInfo['id3v2']['comments']) ?
            $id3TrackInfo['id3v2']['comments']['year'][0] :
            null;
        $out['playtime_string'] = $id3TrackInfo['playtime_string'];
        $out['bitrate'] = $id3TrackInfo['audio']['bitrate'];
        $out['track_number'] = (int)$id3TrackInfo['id3v2']['comments']['track_number'][0];

        return $out;
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
            if (count(str_split($durationParts[0])) == 1) {
                $durationParts[0] = '0' . $durationParts[0];
            }
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
     * Получает изображение по переданному пути. Варианты: Cover,jpg, иное изображение в формате jpg,jpeg,png,gif, no-image.gif
     *
     * @param string $path
     * @return string
     */
    protected function createCover(string $path): string
    {
        $coverOriginalPath = $this->getCoverFromFolder($path);

        return $coverOriginalPath ? $this->saveCover($coverOriginalPath, basename($path)) : self::NO_IMAGE;
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
}
