<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Models\Music\Track;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Http\Requests\Music\Track\PlayRequest;

class TrackController extends Controller
{
    public function play(PlayRequest $request, Track $track): BinaryFileResponse
    {
        return new BinaryFileResponse($track->path_windows);
    }
}
