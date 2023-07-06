<?php

namespace App\Services\Music;

use App\Models\Music\Track;

class TrackService
{
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
}
