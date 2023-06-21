<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Http\Requests\Music\FilterRequest;
use App\Http\Requests\Music\Track\DeleteFromPlaylistRequest;
use App\Http\Requests\Music\Track\RateRequest;
use App\Http\Requests\Music\Track\StoreRequest;
use App\Http\Requests\Music\Track\UpdatePlaylistsRequest;
use App\Http\Resources\Music\Tracks\TrackCollection;
use App\Http\Resources\Music\Tracks\TrackResource;
use App\Models\Music\Track;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Http\Requests\Music\Track\PlayRequest;
use getID3;

class TrackController extends Controller
{
    public function __construct(private getID3 $getID3)
    {

    }

    public function getItems(FilterRequest $request)
    {
        $filters = $request->validated()['filters'] ?? [];

        return new TrackCollection(Track::filterWithCursor($filters));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['name'] = $data['artist'] . ' - ' . $data['name'];
        unset($data['artist']);
        $data['bitrate'] = '320000';
        $data['duration'] = '00:03:00';

        $track = Track::create($data);
        if ($track) {
            $resource = new TrackResource($track);

            return response(['success' => true, 'artists' => $resource], 200);
        } else {
            return response(['success' => false, 'message' => 'Error with creating web track!'], 500);
        }
    }

    public function play(PlayRequest $request, Track $track): string|BinaryFileResponse
    {
        return $track->link ?? new BinaryFileResponse($track->path);
    }

    public function rate(RateRequest $request, Track $track)
    {
        $condition = [
            'user_id' => auth()->user()->id,
            'track_id' => $track->id
        ];

        $data = array_merge(
            ['user_id' => auth()->user()->id],
            $request->validated()
        );

        $result = $track->rate()->updateOrCreate($condition, $data);

        return [
            'track_id' => $result['track_id'],
            'rate' => $result['rate']
        ];
    }

    public function updatePlaylists(Track $track, UpdatePlaylistsRequest $request)
    {
        $playlists = $request->validated()['playlists'];

        $now = date('Y-m-d H:i:s');
        $pivotValues = [
            'user_id' => auth()->user()->id,
            'created_at' => $now,
            'updated_at' => $now
        ];

        $track->playlists()->syncWithPivotValues($playlists, $pivotValues);
    }

    public function deleteFromPlaylist(Track $track, DeleteFromPlaylistRequest $request)
    {
        $playlistId = $request->validated()['playlist'];

        if ($track->playlists()->detach($playlistId)) {
            return ['track_id' => $track->id];
        }
    }

    /**
     * Форматирует длительность трека в 00:05:34, вместо 05:34:00
     * todo Переделать это в отдельный нормальный сервис по парсингу треков
     *
     * @param string $duration
     * @return string
     */
    protected function formatDuration(string $duration): string
    {
        $durationParts = explode(':', $duration);

        if (count($durationParts) == 2) {
            $duration = '00:' . $durationParts[0] . ':' . $durationParts[1];
        }

        return $duration;
    }
}
