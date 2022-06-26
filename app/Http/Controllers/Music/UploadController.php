<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Http\Requests\Music\Upload\UploadRequest;
use App\Services\ParseFolderService;

class UploadController extends Controller
{
    /**
     * @var ParseFolderService
     */
    private $parseFolderService;

    public function __construct(ParseFolderService $parseFolderService)
    {
        $this->parseFolderService = $parseFolderService;
    }

    public function upload(UploadRequest $request)
    {
        dd($this->parseFolderService->collectData($request->validated()['folder']));
    }
}
