<?php

namespace App\Models\Widgets\Models;

use App\Models\Music\Album;
use App\Models\Music\Artist;
use App\Models\Music\Track;
use App\Models\Widgets\Widget;

class MusicWidget extends Widget implements WidgetInterface
{
    public function getWidget(): array
    {
        return [
            'artistsCount' => Artist::count(),
            'albumsCount' => Album::count(),
            'tracksCount' => Track::count(),
        ];
    }
}
