<?php

namespace App\Services\Music;

use App\Models\Music\MusicTag;

class TagService
{
    protected $tag;

    public function __construct(MusicTag $tag)
    {
        $this->tag = $tag;
    }
}
