<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Music\Artist\TracksRequest;
use App\Http\Requests\Music\Track\IndexRequest;
use App\Http\Requests\Music\Track\DeleteFromPlaylistRequest;
use App\Http\Requests\Music\Track\PlayRequest;
use App\Http\Requests\Music\Track\RateRequest;
use App\Http\Requests\Music\Track\StoreRequest;
use App\Http\Requests\Music\Track\UpdatePlaylistsRequest;
use App\Http\Resources\Music\Tracks\TrackCollection;
use App\Http\Resources\Music\Tracks\TrackResource;
use App\Models\Music\Track;
use App\Services\Music\TrackService;
use getID3;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TrackController extends BaseController
{
    public function __construct(
        private getID3 $getID3,
        private TrackService $trackService
    )
    {
    }

    public function index(IndexRequest $request)
    {
        $filters = $request->validated()['filters'] ?? [];

        return new TrackCollection(Track::filterWithCursor($filters));
    }

    public function store(StoreRequest $request)
    {
        try {
            $out = $this->trackService->storeTrack($request->validated());

            return $this->sendResponse(new TrackResource($out), 'Track Stored Successfully!');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function rate(Track $track, RateRequest $request)
    {
        try {
            $out = $this->trackService->rateTrack($track, $request->validated());

            return $this->sendResponse([
                'track_id' => $out['track_id'],
                'rate' => $out['rate']
            ], 'Track rated successfully!');
        } catch (\Exception $exception) {
            $this->sendError($exception->getMessage());
        }
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

    public function play(PlayRequest $request, Track $track): string|BinaryFileResponse
    {
        return $track->link ?? new BinaryFileResponse($track->path);
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
