<?php

namespace App\Services\Music\Parse;

use App\Models\Music\Artist;
use App\Models\Music\MusicTag;

class ParseMusicFolders extends BaseMusicParse
{
    public function __construct()
    {
        parent::__construct();
    }

    public function upload(string $folder): bool
    {
        if (file_exists($folder)) {
            return $this->parseFolder($folder);
        } else {
            throw new \Exception('The chosen catalog doesn\'t exists!');
        }
    }

    private function parseFolder(string $folder): bool
    {
        $albums = $this->findAlbums($folder);

        if (!empty($albums)) {
            foreach ($albums as $albumPath) {
                $albumItems = scandir($albumPath);

                $coverPath = $this->getCover($albumPath);

                foreach ($albumItems as $item) {
                    if ($item === '.' || $item === '..') {
                        continue;
                    }

                    $info = pathinfo($item);

                    if (isset($info['extension']) && in_array($info['extension'], self::TRACK_EXTENSIONS)) {
                        $trackPath = $albumPath . DIRECTORY_SEPARATOR . $item;
                        $id3TrackInfo = $this->getID3->analyze($trackPath);

                        $artistName = $id3TrackInfo['id3v2']['comments']['artist'][0];

                        $dataForArtist = ['path' => $folder];
                        if ($coverPath != self::NO_IMAGE) {
                            $dataForArtist['image'] = $coverPath;
                        }
                        // Добавляем исполнителя по имени
                        $artist = Artist::updateOrCreate([
                            'name' => $artistName
                        ], $dataForArtist);

                        $albumName = $id3TrackInfo['id3v2']['comments']['album'][0];

                        $album = $artist->albums()->updateOrCreate([
                            'name' => $albumName
                        ],[
                            'image' => $coverPath,
                            'year' => $id3TrackInfo['id3v2']['comments']['year'][0],
                            'path' => $albumPath
                        ]);

                        $trackName = $id3TrackInfo['id3v2']['comments']['title'][0];

                        $track = $album->tracks()->updateOrCreate([
                            'album_id' => $album->id,
                            'name' => $trackName
                        ],[
                            'number' => $id3TrackInfo['id3v2']['comments']['track_number'][0] ?? NULL,
                            'duration' => $this->formatDuration($id3TrackInfo['playtime_string']),
                            'bitrate' => $id3TrackInfo['audio']['bitrate'],
                            'path' => $trackPath,
                            'image' => $coverPath,
                        ]);

                        $genres = $id3TrackInfo['id3v2']['comments']['genre'];

                        if (count($genres) > 0) {
                            $ids = $this->getTagsIds($genres);
                            $track->tags()->sync($ids);
                        }
                    }
                }
            }
        } else {
            throw new \Exception('There is no albums here!');
        }

        return true;
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
}
