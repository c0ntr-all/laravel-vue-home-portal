<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Music\Artist\UploadRequest;
use App\Services\Music\Parse\ParseMusicFolders;

class UploadController extends Controller
{
    public function __construct()
    {
    }

    public function upload(UploadRequest $request)
    {
        return (new ParseMusicFolders())->upload($request->validated()['folder']);
    }
}
