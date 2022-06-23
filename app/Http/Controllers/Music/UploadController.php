<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Http\Requests\Music\Upload\UploadRequest;

class UploadController extends Controller
{
    public function upload(UploadRequest $request)
    {
        return $request->validated();
    }
}
