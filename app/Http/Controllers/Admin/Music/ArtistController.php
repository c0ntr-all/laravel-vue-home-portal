<?php

namespace App\Http\Controllers\Admin\Music;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Music\Artist\IndexRequest;
use App\Http\Requests\Admin\Music\Artist\UploadRequest;
use App\Http\Requests\Music\Artist\StoreRequest;
use App\Http\Requests\Music\Artist\UpdateRequest;
use App\Http\Resources\Music\Artists\AdminArtistCollection;
use App\Http\Resources\Music\Artists\AdminArtistResource;
use App\Models\Music\Artist;
use App\Services\Music\ArtistService;
use App\Services\Music\Parse\ArtistParseService;

class ArtistController extends BaseController
{
    public function __construct(
        private readonly ArtistService      $artistService,
        private readonly ArtistParseService $artistParseService,
    )
    {
    }

    public function index(IndexRequest $request): \Illuminate\Http\Response
    {
        $out = $this->artistService->getWithPaginate($request->validated());

        return $this->sendResponse(new AdminArtistCollection($out));
    }

    public function store(StoreRequest $request)
    {
        $out = $this->artistService->saveArtist($request->validated());
        $message = 'Исполнитель ' . $out->name . ' успешно добавлен!';

        return $this->sendResponse(new AdminArtistResource($out), $message);
    }

    public function update(Artist $artist, UpdateRequest $request)
    {
        $out = $this->artistService->updateArtist($artist, $request->validated());

        return $this->sendResponse($out, 'Artist updated successfully!');
    }

    /**
     * @throws \Throwable
     */
    public function upload(UploadRequest $request)
    {
        $out = $this->artistParseService->process($request->validated());

        if ($out) {
            return $this->sendResponse($out, 'Artist uploaded successfully!');
        }
    }
}
