<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Http\Requests\Music\Track\RateRequest;
use App\Models\Music\Track;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Http\Requests\Music\Track\PlayRequest;

class TrackController extends Controller
{
    public function play(PlayRequest $request, Track $track): BinaryFileResponse
    {
        return new BinaryFileResponse($track->path_windows);
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
}
