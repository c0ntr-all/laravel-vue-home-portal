<?php declare(strict_types=1);

namespace App\Services\Music\Parse;

use App\Data\Music\AlbumCreateData;
use App\Data\Music\ArtistCreateData;
use App\Data\Music\TrackCreateData;
use App\Events\TrackParsed;
use App\Models\Music\Artist;
use App\Models\Music\MusicTag;
use App\Services\Music\Albums\AlbumService;
use App\Services\Music\ArtistService;
use App\Traits\Makeable;
use getID3;
use Illuminate\Support\Facades\DB;

class ArtistParseService
{
    use Makeable;

    private string $path;
    private bool $preview;
    private array $data = [];
    private array $tags = [];
    protected getID3 $getID3;

    protected const NO_IMAGE = 'no-image.gif';
    protected const DEFAULT_COVER_NAME = 'Cover.jpg';
    protected const COVER_EXTENSIONS = ['jpg','jpeg','png','gif'];
    protected const TRACK_EXTENSIONS = ['mp3'];

    public function __construct(
        private readonly ArtistService $artistService,
        private readonly AlbumService $albumService,
    )
    {
        $this->getID3 = new getID3();
    }

    /**
     * @throws \Throwable
     */
    public function process($data)
    {
        $this->path = $data['path'];

        if (!empty($data['is_preview'])) {
            return $this->preview();
        } else {
            return $this->upload();
        }
    }

    /**
     * @throws \Exception
     */
    public function preview(): array
    {
        return $this->parseFolder();
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function upload()
    {
        $data = $this->parseFolder();

        $this->prepareTagsIds();

        return DB::transaction(function () use ($data) {
            foreach ($data as $artistData) {
                $albumCover = null;

                $artistDto = ArtistCreateData::from($artistData);
                $artistDto->user_id = auth()->user()->id;
                $artist = $this->artistService->saveArtist($artistDto);

                foreach($artistData['albums'] as $albumData) {
                    $albumDto = AlbumCreateData::from($albumData);
                    $albumDto->user_id = auth()->user()->id;
                    $album = $this->albumService->saveAlbum($artist, $albumDto);

                    foreach($albumData['tracks'] as $trackData) {
                        $trackDto = TrackCreateData::from($trackData);
                        $trackDto->user_id = auth()->user()->id;
                        //todo: Формирование Version и CD и прикрепление к ним треков
                        $track = $album->tracks()->updateOrCreate([
                            'album_id' => $album->id,
                            'name' => $trackData['name']
                        ],[
                            'number' => $trackData['number'],
                            'duration' => $trackData['duration'],
                            'bitrate' => $trackData['bitrate'],
                            'path' => $trackData['path'],
                            'image' => $albumCover,
                        ]);

//                        $socketData = [
//                            'artist' => $artist->name,
//                            'album' => $album->name,
//                            'track' => $trackData['name']
//                        ];

                        if ($trackData['genre'] && array_key_exists($trackData['genre'], $this->tags)) {
                            $track->tags()->syncWithoutDetaching($this->tags[$trackData['genre']]);
                            $album->tags()->syncWithoutDetaching($this->tags[$trackData['genre']]);
                            $artist->tags()->syncWithoutDetaching($this->tags[$trackData['genre']]);
                        }

//                        TrackParsed::dispatch($socketData);
                    }
                }
                // Добавляем обложку исполнителю на этом этапе, чтобы взять готовую с альбома и не дублировать ее для исполнителя
                $artist->update(['image' => $albumCover]);
            }

            return ['artists' => collect($data)->pluck('name')];
        });
    }

    /**
     * @throws \Exception
     */
    public function parseFolder(): array
    {
        $albums = $this->findAlbums($this->path);

        if (!empty($albums)) {
            foreach ($albums as $albumPath) {
                $cover = $this->getCoverFromFolder($albumPath);

                $albumItems = scandir($albumPath);

                foreach ($albumItems as $item) {
                    if ($item === '.' || $item === '..') {
                        continue;
                    }

                    $itemPath = $albumPath . DIRECTORY_SEPARATOR . $item;
                    $itemInfo = pathinfo($item);

                    if (isset($itemInfo['extension']) && in_array($itemInfo['extension'], self::TRACK_EXTENSIONS)) {
                        $this->addDataByTrack($itemPath, $albumPath, $cover);
                    }
                }
            }
        }

        return $this->data;
    }

    private function addDataByTrack($trackPath, $albumPath, $cover = null): void
    {
        $id3TrackInfo = $this->getId3Tags($trackPath);

        $artistName = $id3TrackInfo['artist'] ?? basename($this->path);
        $albumName = $id3TrackInfo['album'];
        $albumCd = $id3TrackInfo['cd'];

        if (empty($artistName)) {
            throw new \Exception('Track has no artist info! Path: ' . $trackPath);
        }

        if (empty($albumName)) {
            throw new \Exception('Track has no album info! Path: ' . $trackPath);
        }

        if (empty($id3TrackInfo['title'])) {
            throw new \Exception('Track has no title! Path: ' . $trackPath);
        }

        // Adding tags to the general array
        if (!in_array($id3TrackInfo['genre'], $this->tags)) {
            $this->tags[] = $id3TrackInfo['genre'];
        }

        $track = [
            'name' => $id3TrackInfo['title'],
            'number' => $id3TrackInfo['track_number'],
            'genre' => $id3TrackInfo['genre'],
            'duration' => $this->formatDuration($id3TrackInfo['playtime_string']),
            'bitrate' => $id3TrackInfo['bitrate'],
            'path' => $trackPath,
            'uploaded' => false
        ];
        $album = [
            'name' => $albumName,
            'cd' => $albumCd,
            'image' => $cover,
            'date' => $this->prepareDate($id3TrackInfo['year']),
            'is_date_verified' => false,
            'path' => $albumPath,
        ];
        $artist = [
            'name' => $artistName,
            'path' => $this->path,
            'image' => $cover,
        ];

        if (!in_array($artistName, array_column($this->data, 'name'))) {
            $this->data[] = $artist;
        }

        foreach ($this->data as $artistKey => $artistValue) {
            if ($artistValue['name'] == $artistName) {
                if (!isset($this->data[$artistKey]['albums'])) {
                    $this->data[$artistKey]['albums'] = [];
                }
                if (!array_reduce($this->data[$artistKey]['albums'], function($carry, $album) use ($albumName, $albumCd) {
                    return $carry || ($album['name'] === $albumName && $album['cd'] === $albumCd);
                }, false)) {
                    $this->data[$artistKey]['albums'][] = $album;
                }
                foreach ($this->data[$artistKey]['albums'] as $albumKey => $albumValue) {
                    if ($albumValue['name'] == $albumName && $id3TrackInfo['cd'] == $albumValue['cd']) {
                        $this->data[$artistKey]['albums'][$albumKey]['tracks'][] = $track;
                    }
                }
            }
        }
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
                    // Ищем папки CD 1, CD 2 и т.д. если есть
                    $subFolders = scandir($dirItem);
                    $cdFolders = array_filter($subFolders, function ($subFolder) {
                        return preg_match('/^CD \d+$/', $subFolder);
                    });
                    if (!empty($cdFolders)) {
                        foreach ($cdFolders as $cd) {
                            $items[] = $dirItem . DIRECTORY_SEPARATOR . $cd;
                        }
                    } else {
                        $items[] = $dirItem;
                    }
                } else if(is_dir($dirItem)) {
                    $this->findAlbums($dirItem);
                }
            }
        }

        return $items;
    }

    /**
     * Converting only tags names to tags names and ids
     *
     * @return void
     */
    private function prepareTagsIds(): void
    {
        $this->tags = MusicTag::whereIn('name', $this->tags)->get()?->pluck('id', 'name')->toArray();
    }

    private function prepareDate(string $dateString): ?string
    {
        // Проверяем, является ли строка датой формата Y (например, "1999")
        if (preg_match('/^\d{4}$/', $dateString)) {
            return $dateString . '-01-01';
        }

        // Проверяем, является ли строка датой формата Y-m-d
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateString)) {
            return $dateString;
        }

        // Если строка не соответствует ни одному из форматов, возвращаем NULL
        return NULL;
    }

    /**
     * Получает все небходимые теги из файлов с помощью ID3
     *
     * @param string $path
     * @return array
     */
    private function getId3Tags(string $path): array
    {
        $id3TrackInfo = $this->getID3->analyze($path);

        return [
            'artist' => $id3TrackInfo['id3v2']['comments']['artist'][0] ?? null,
            'album' => $id3TrackInfo['id3v2']['comments']['album'][0] ?? null,
            'title' => $id3TrackInfo['id3v2']['comments']['title'][0] ?? null,
            'genre' => $id3TrackInfo['id3v2']['comments']['genre'][0] ?? null,
            'year' => $id3TrackInfo['id3v2']['comments']['year'][0] ?? null,
            'cd' => $id3TrackInfo['id3v2']['comments']['part_of_a_set'][0] ?? null,
            'playtime_string' => $id3TrackInfo['playtime_string'] ?? null,
            'bitrate' => $id3TrackInfo['audio']['bitrate'] ?? null,
            'track_number' => (int) ($id3TrackInfo['id3v2']['comments']['track_number'][0] ?? 0),
        ];
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
     * @param string|null $duration
     * @return string|null
     */
    protected function formatDuration(?string $duration): ?string
    {
        if (!$duration) {
            return null;
        }

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
    protected function getCoverFromFolder(string $albumPath): ?string
    {
        $defaultCover = $albumPath . DIRECTORY_SEPARATOR . 'Cover.jpg';

        return file_exists($defaultCover) ? $defaultCover : $this->findAnotherImageAsCover($albumPath);
    }
}
