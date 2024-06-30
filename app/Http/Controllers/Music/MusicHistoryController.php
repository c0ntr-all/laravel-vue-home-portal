<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Music\MusicHistory\StoreRequest;
use App\Http\Resources\Client\Music\History\MusicHistoryCollection;
use App\Models\Music\MusicHistory;
use App\Services\Music\TrackService;

class MusicHistoryController extends BaseController
{
    public function __construct(private TrackService $service)
    {
    }

    public function index(): MusicHistoryCollection
    {
        $historyItems = $this->service->getHistory();

        return new MusicHistoryCollection($historyItems);
    }

    public function scrobble(StoreRequest $request)
    {
        $historyItem = MusicHistory::create(array_merge($request->validated(), ['user_id' => auth()->user()->id]));

        return $this->sendResponse($historyItem, 'Track successfully scrobbled!');
    }
}
