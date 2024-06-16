<?php

namespace App\Services\Music;

use App\Models\Music\MusicHistory;
use App\Models\Music\Track;

class TrackService
{
    //todo: Доработать или удалить. Убрать зависимость от $requestData
    public function storeTrack(array $requestData)
    {
        $requestData['name'] = $requestData['artist'] . ' - ' . $requestData['name'];
        unset($requestData['artist']);
        $requestData['bitrate'] = '320000';
        $requestData['duration'] = '00:03:00';

        return Track::create($requestData);
    }

    public function rateTrack(Track $track, array $requestData)
    {
        return $track->rate()->updateOrCreate([
            'user_id' => auth()->user()->id,
            'track_id' => $track->id
        ], array_merge(
            ['user_id' => auth()->user()->id],
            $requestData
        ));
    }

    public function scrobbleTrack(array $requestData)
    {
        return MusicHistory::create(array_merge($requestData, ['user_id' => auth()->user()->id]));
    }

    public function getHistory()
    {
        return auth()->user()->musicHistory()->get();
    }
}
