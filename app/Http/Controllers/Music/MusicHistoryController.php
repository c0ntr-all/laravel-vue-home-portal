<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Http\Requests\Music\MusicHistory\StoreRequest;
use App\Models\Music\MusicHistory;
use App\Http\Resources\Music\MusicHistory\MusicHistoryCollection;

class MusicHistoryController extends Controller
{
    public function getItems(): MusicHistoryCollection
    {
        $historyItems = MusicHistory::getHistory(auth()->user()->id);

        return new MusicHistoryCollection($historyItems);
    }

    public function scrobble(StoreRequest $request)
    {
        $historyItem = MusicHistory::create(array_merge($request->validated(), ['user_id' => auth()->user()->id]));

        if ($historyItem) {
            return response([
                'success' => true,
                'message' => 'Track successfully scrobbled!',
                'data' => $historyItem
            ], 200);
        } else {
            return response([
                'success' => false,
                'message' => 'Something went wrong during storing scrobble!'
            ], 500);
        }
    }
}
