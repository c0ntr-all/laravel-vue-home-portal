<?php

namespace App\Services\Music;

use App\Models\Music\Tag;

class TagService
{
    protected $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }
}
