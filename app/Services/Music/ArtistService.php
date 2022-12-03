<?php

namespace App\Services\Music;

use App\Models\Music\Artist;

class ArtistService {

    protected $artist;

    public function __construct(Artist $artist)
    {
        $this->artist = $artist;
    }
}
