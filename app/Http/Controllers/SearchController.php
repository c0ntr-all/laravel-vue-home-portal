<?php

namespace App\Http\Controllers;

use App\Http\Requests\Search\SearchRequest;
use App\Http\Resources\Admin\Music\Artists\ArtistCollection;
use App\Models\Music\Artist;

class SearchController extends Controller
{
    public function search(SearchRequest $request)
    {
        $artists = Artist::where('name', 'LIKE', "%$request->name%")->with('tags')->paginate(100);

        return $this->artistsResponse($artists);
    }

    protected function artistsResponse($artists): array
    {
        $resources = new ArtistCollection($artists);

        return ['success' => true, 'data' => $resources];
    }
}
