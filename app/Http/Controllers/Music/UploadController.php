<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Http\Requests\Music\Upload\UploadRequest;
use App\Jobs\MusicParseArtistJob;

class UploadController extends Controller
{
    public function __construct()
    {
    }

    public function upload(UploadRequest $request)
    {
        dispatch(new MusicParseArtistJob($request->validated()['folder']));
    }
}
