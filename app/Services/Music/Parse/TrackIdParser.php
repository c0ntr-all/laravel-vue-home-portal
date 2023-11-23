<?php declare(strict_types=1);

namespace App\Services\Music\Parse;

use App\Models\Music\MusicTag;
use App\Traits\Makeable;

class TrackIdParser extends BaseMusicParseService
{
    use Makeable;

    private string $path;
    private array $data;

    public function __construct(string $path)
    {
        parent::__construct();

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
                $cover = $this->createCover($albumPath);
                $albumItems = scandir($albumPath);

                foreach ($albumItems as $key => $item) {
                    if ($item === '.' || $item === '..') {
                        continue;
                    }

                    $info = pathinfo($item);

                    if (isset($info['extension']) && in_array($info['extension'], self::TRACK_EXTENSIONS)) {
                        $trackPath = $albumPath . DIRECTORY_SEPARATOR . $item;
                        $id3TrackInfo = $this->getID3->analyze($trackPath);

                        $artistName = $id3TrackInfo['id3v2']['comments']['artist'][0];
                        $albumName = $id3TrackInfo['id3v2']['comments']['album'][0];
                        $trackName = $id3TrackInfo['id3v2']['comments']['title'][0];

                        $track = [
                            'name' => $trackName,
                            'number' => $id3TrackInfo['id3v2']['comments']['track_number'][0] ?? NULL,
                            'duration' => $this->formatDuration($id3TrackInfo['playtime_string']),
                            'bitrate' => $id3TrackInfo['audio']['bitrate'],
                            'path' => $trackPath,
                            'image' => $cover,
                        ];
                        $album = [
                            'name' => $albumName,
                            'image' => $cover,
                            'year' => $id3TrackInfo['id3v2']['comments']['year'][0],
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
}
