<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Http\Requests\Music\Album\IndexRequest;
use App\Models\Music\Album;
use App\Http\Resources\Music\AlbumResource;
use Illuminate\Http\Response;

class AlbumController extends Controller
{

    public function index(IndexRequest $request): array|Response
    {
        return $this->albumResponse(Album::find($request->validated()['id']));
    }

    public function albumResponse($album): array|Response
    {
        if ($album) {
            $resource = new AlbumResource($album);

            return ['success' => true, 'data' => $resource];
        } else {
            return response(['success' => false, 'message' => 'Album not Found!'], 404);
        }
    }
}
