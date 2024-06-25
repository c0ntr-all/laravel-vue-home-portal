<?php declare(strict_types=1);

namespace App\Services\Music\Parse;

use App\Data\Music\AlbumCreateData;
use App\Data\Music\ArtistCreateData;
use App\Data\Music\TrackCreateData;
use App\Models\Music\MusicUploadHistory;
use App\Repositories\TagRepository;
use App\Services\Music\AlbumService;
use App\Services\Music\ArtistService;
use App\Services\Music\TrackService;
use Illuminate\Support\Facades\DB;

readonly class MusicUploadService
{
    public function __construct(
        private MusicParseService $musicParseService,
        private ArtistService $artistService,
        private AlbumService $albumService,
        private TrackService $trackService,
        private TagRepository $tagRepository,
    )
    {
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function process(string $path)
    {
        $data = $this->musicParseService->process($path);

        return DB::transaction(function () use ($data) {
            $existingTags = $this->prepareTagsIds();

            foreach ($data as $artistData) {
                $artistDto = ArtistCreateData::from($artistData);
                $artistDto->user_id = auth()->user()->id;
                //todo: Переделать сохранение изображения. Оно происходит не своевременно
                $artist = $this->artistService->saveArtist($artistDto);

                foreach($artistData['albums'] as $albumData) {
                    $albumDto = AlbumCreateData::from($albumData);
                    $albumDto->user_id = auth()->user()->id;
                    if (isset($albumData['original_album'])) {
                        $originalAlbum = $albumData['original_album'];
                        $existingOriginalAlbum = $artist->albums()->where('name', 'like', '%' . $originalAlbum . '%')->first();

                        if ($existingOriginalAlbum) {
                            $albumDto->parent_id = $existingOriginalAlbum->id;
                        }
                    }
                    $album = $this->albumService->saveAlbum($artist, $albumDto);
                    $artist->albums()->syncWithoutDetaching([$album->id]);

                    foreach($albumData['tracks'] as $trackData) {
                        $trackDto = TrackCreateData::from($trackData);
                        $trackDto->user_id = auth()->user()->id;
                        $trackDto->image = $albumDto->image;
                        $track = $this->trackService->saveTrack($album, $trackDto);

//                        $socketData = [
//                            'artist' => $artist->name,
//                            'album' => $album->name,
//                            'track' => $trackData['name']
//                        ];

                        if ($trackData['genre'] && array_key_exists($trackData['genre'], $existingTags)) {
                            $track->tags()->syncWithoutDetaching($existingTags[$trackData['genre']]);
                            $album->tags()->syncWithoutDetaching($existingTags[$trackData['genre']]);
                            $artist->tags()->syncWithoutDetaching($existingTags[$trackData['genre']]);
                        }

//                        TrackParsed::dispatch($socketData);
                    }
                }
            }

            $this->saveUploadHistory($data);

            return ['artists' => collect($data)->pluck('name')];
        });
    }

    /**
     * Preparing tags names and ids
     *
     * @return array|null
     */
    private function prepareTagsIds(): ?array
    {
        return $this->tagRepository->getTags()?->pluck('id', 'name')->toArray();
    }

    private function saveUploadHistory(array $data): void
    {
        MusicUploadHistory::create(['data' => json_encode($data, JSON_UNESCAPED_UNICODE)]);
    }
}
