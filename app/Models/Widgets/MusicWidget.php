<?php

namespace App\Models\Widgets;

use App\Models\Music\Artist;
use App\Models\Music\Album;
use App\Models\Music\Track;

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
