<?php

namespace App\Observers;

use App\Models\Tag;

class TagObserver
{
    public function creating(Tag $tag): void
    {
        $this->setSlug($tag);
    }

    public function updating(Tag $tag): void
    {
        $tag->slug = \Str::slug($tag->name);
    }

    protected function setSlug(Tag $tag): void
    {
        $tag->slug = \Str::slug($tag->name);
    }
}
