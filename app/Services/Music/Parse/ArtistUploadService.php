<?php declare(strict_types=1);

namespace App\Services\Music\Parse;

use App\Data\Music\AlbumCreateData;
use App\Data\Music\ArtistCreateData;
use App\Data\Music\TrackCreateData;
use App\Models\Music\MusicTag;
use Illuminate\Support\Facades\DB;

readonly class ArtistUploadService
{
    public function __construct(
        private MusicParseTrackService $artistParseService
    )
    {
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function upload(string $path)
    {
        $data = $this->artistParseService->parseFolder($path);

        return DB::transaction(function () use ($data) {
            $existingTags = $this->prepareTagsIds();

            foreach ($data as $artistData) {
                $albumCover = null;

                $artistDto = ArtistCreateData::from($artistData);
                $artistDto->user_id = auth()->user()->id;
                $artist = $this->artistService->saveArtist($artistDto);

                foreach($artistData['albums'] as $albumData) {
                    $albumDto = AlbumCreateData::from($albumData);
                    $albumDto->user_id = auth()->user()->id;
                    $album = $this->albumService->saveAlbum($artist, $albumDto);

                    $albumCover = $album->image;

                    foreach($albumData['tracks'] as $trackData) {
                        $trackDto = TrackCreateData::from($trackData);
                        $trackDto->user_id = auth()->user()->id;
                        //todo: Формирование Version и CD и прикрепление к ним треков
                        //todo: Парсинг и хранение ремастеров с укзаанием года
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
                            $track->tags()->syncWithoutDetaching($existingTags[$trackData['genre']]);
                            $album->tags()->syncWithoutDetaching($existingTags[$trackData['genre']]);
                            $artist->tags()->syncWithoutDetaching($existingTags[$trackData['genre']]);
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
     * Converting only tags names to tags names and ids
     * todo: Вынести в репозиторий
     *
     * @return array|null
     */
    private function prepareTagsIds(): ?array
    {
        return MusicTag::whereIn('name', $this->tags)->get()?->pluck('id', 'name')->toArray();
    }
}
