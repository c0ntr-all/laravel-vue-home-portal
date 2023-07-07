<?php

namespace App\Http\Controllers\Music\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Music\Artist\IndexRequest;
use App\Http\Requests\Music\Artist\StoreRequest;
use App\Http\Requests\Music\Artist\UpdateRequest;
use App\Http\Resources\Music\Artists\AdminArtistCollection;
use App\Http\Resources\Music\Artists\AdminArtistResource;
use App\Models\Music\Artist;
use App\Services\Music\ArtistService;

class ArtistController extends BaseController
{
    protected $artists;

    public function __construct(private ArtistService $artistService)
    {
    }

    public function index(IndexRequest $request) {
        $out = $this->artistService->getWithPaginate($request->validated());

        return $this->sendResponse(new AdminArtistCollection($out));
    }

    public function store(StoreRequest $request)
    {
        try {
            $out = $this->artistService->storeArtist($request->validated());
            $message = 'Исполнитель ' . $out->name . ' успешно добавлен!';

            return $this->sendResponse(new AdminArtistResource($out), $message);
        } catch (\Exception $exception) {
            return $this->sendError('Failed to store Artist: ' . $exception->getMessage());
        }
    }

    public function update(Artist $artist, UpdateRequest $request)
    {
        try {
            $out = $this->artistService->updateArtist($artist, $request->validated());

            return $this->sendResponse($out, 'Artist updated successfully!');
        } catch (\Exception $exception) {
            return $this->sendError('Failed to update Artist: ' . $exception->getMessage());
        }
    }
}
