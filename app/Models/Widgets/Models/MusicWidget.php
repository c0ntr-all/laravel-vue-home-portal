<?php

namespace App\Models\Widgets\Models;

use App\Models\Music\Album;
use App\Models\Music\Artist;
use App\Models\Music\Track;
use App\Models\Widgets\Widget;

/**
 * App\Models\Widgets\Models\MusicWidget
 *
 * @property-read \App\Models\Widgets\WidgetPlacement|null $placement
 * @method static \Illuminate\Database\Eloquent\Builder|MusicWidget newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MusicWidget newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MusicWidget query()
 * @mixin \Eloquent
 */
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
