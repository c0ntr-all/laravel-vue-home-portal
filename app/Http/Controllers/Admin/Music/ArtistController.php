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
use App\Services\Music\Parse\MusicUploadService;
use App\Services\Music\Parse\MusicParseService;
use Illuminate\Http\Response;

class ArtistController extends BaseController
{
    public function __construct(
        private readonly ArtistService      $artistService,
        private readonly MusicParseService  $musicParseService,
        private readonly MusicUploadService $artistUploadService,
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
    public function upload(UploadRequest $request): Response
    {
        if ($request->is_preview) {
            $out = $this->musicParseService->process($request->path);
            $message = 'Data prepared successfully!';
        } else {
            $out = $this->artistUploadService->process($request->path);
            $message = 'Artist uploaded successfully!';
        }

        if (!empty($out)) {
            return $this->sendResponse($out, $message);
        } else {
            return $this->sendError('No data!', 404);
        }
    }
}
