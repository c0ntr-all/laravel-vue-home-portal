<?php

namespace App\Services\Music;

class ArtistService {

    protected $artist;

    public function __construct(Artist $artist)
    {
        $this->artist = $artist;
    }
}
