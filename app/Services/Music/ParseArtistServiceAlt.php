<?php

namespace App\Services\Music;

use App\Models\Music\Artist;
use App\Helpers\ImageUpload;
use getID3;

class ParseArtistServiceAlt
{
    private string $noImage = 'no-image.gif';

    private const EXTENSIONS = ['mp3'];

    public function __construct(private getID3 $getID3)
    {

    }

    public function upload(string $folder)
    {
        if (file_exists($folder)) {
            $items = $this->parseFolder($folder);

            return $items;
        }

        throw new \Exception('The chosen catalog doesn\'t exists!');
    }

    private function parseFolder(string $folder)
    {
        $albums = $this->findAlbums($folder);

        if (!empty($albums)) {
            foreach ($albums as $albumPath) {
                $albumItems = scandir($albumPath);

                $cover = $this->getCover($albumPath);

                foreach ($albumItems as $key => $item) {
                    if ($item === '.' || $item === '..') {
                        continue;
                    }

                    $info = pathinfo($item);

                    if (isset($info['extension']) && $info['extension'] == 'mp3') {
                        $trackPath = $albumPath . DIRECTORY_SEPARATOR . $item;
                        $id3TrackInfo = $this->getID3->analyze($trackPath);

                        $artistName = $id3TrackInfo['id3v2']['comments']['artist'][0];

                        // Добавляем исполнителя по имени
                        $artist = Artist::firstOrCreate([
                            'name' => $artistName
                        ],[
                            'image' => $cover,
                            'path' => $folder
                        ]);

                        $albumName = $id3TrackInfo['id3v2']['comments']['album'][0];

                        $album = $artist->albums()->firstOrCreate([
                            'name' => $albumName
                        ],[
                            'image' => $cover,
                            'year' => $id3TrackInfo['id3v2']['comments']['year'][0],
                            'path' => $albumPath
                        ]);

                        $trackName = $id3TrackInfo['id3v2']['comments']['title'][0];

                        $album->tracks()->updateOrCreate([
                            'album_id' => $album->id,
                            'name' => $trackName
                        ],[
                            'number' => $id3TrackInfo['id3v2']['comments']['track_number'][0] ?? NULL,
                            'duration' => $id3TrackInfo['playtime_string'],
                            'bitrate' => $id3TrackInfo['audio']['bitrate'],
                            'path' => $trackPath
                        ]);
                    }
                }
            }
        }

        $dirCanonical = realpath($folder);

        static $items = [];

        if ($fileOrDir = opendir($dirCanonical)) {
            while (false !== ($fileName = readdir($fileOrDir))) {
                if ($fileName == "." || $fileName == "..") {
                    continue;
                }

                $dirItem = $dirCanonical . DIRECTORY_SEPARATOR . $fileName;
                $info = pathinfo($dirItem);

                if (is_dir($dirItem)) {
                    $this->parseFolder($dirItem);
                }
            }

            closedir($fileOrDir);
        }

        return $items;
    }

    private function getAlbumDir($file, $count = 0): string|null
    {
        $count++;

        $firstLevelDir = dirname($file);

        if (preg_match('/[0-9]{4} - .*/i', basename($firstLevelDir))) {
            return $firstLevelDir;
        } else {
            if ($count < 3) {
                return $this->getAlbumDir($firstLevelDir, $count);
            } else {
                return null;
            }
        }
    }

    public function findAlbums(string $path): array
    {
        $dirCanonical = realpath($path);

        static $items = [];

        if ($dirStream = opendir($dirCanonical)) {
            while (false !== ($fileName = readdir($dirStream))) {
                if ($fileName == "." || $fileName == "..") {
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

    private function getCover(string $albumPath): string|null
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
