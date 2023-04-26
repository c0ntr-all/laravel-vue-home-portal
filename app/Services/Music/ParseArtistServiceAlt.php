<?php

namespace App\Services\Music;

use App\Models\Music\Artist;
use App\Models\Music\Album;
use App\Models\Music\Track;
use App\Helpers\ImageUpload;
use getID3;
use Illuminate\Http\File;

class ParseArtistServiceAlt
{
    private string $noImage = 'no-image.gif';

    private const EXTENSIONS = ['mp3'];

    private const TYPES = ['lp', 'ep', 'single', 'demo', 'split', 'tribute', 'bootleg', 'live', 'instrumental', 'remaster'];

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
                } else {
                    if ($info['extension'] === 'mp3') {
                        $albumDir = $this->getAlbumDir($dirItem);

                        $coverOriginalPath = $albumDir . DIRECTORY_SEPARATOR . 'Cover.jpg';

                        if (file_exists($coverOriginalPath)) {

//                            $imageObject = ImageUpload::make()
//                                                      ->setDiskName('public')
//                                                      ->setFolder('music/albums/posters')
//                                                      ->setSourceName(basename($albumDir));

//                            if ($imageObject->exists('jpg')) {
//
//                            }

                            $items[] = [
                                'track' => $dirItem,
                                'cover' => ImageUpload::make()
                                                      ->setDiskName('public')
                                                      ->setFolder('music/albums/posters')
                                                      ->setSourceName(basename($albumDir))
                                                      ->getFilePath('jpg')
                            ];
                        }
                    }
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
}
