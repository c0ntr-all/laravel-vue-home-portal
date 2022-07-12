<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Http\Requests\Music\Upload\UploadRequest;
use App\Services\Music\ParseArtistService;

class UploadController extends Controller
{
    /**
     * @var ParseArtistService
     */
    private $parseArtistService;

    public function __construct(ParseArtistService $parseArtistService)
    {
        $this->parseArtistService = $parseArtistService;
    }

    public function upload(UploadRequest $request)
    {
        return $this->parseArtistService->upload($request->validated()['folder']);
    }
}
