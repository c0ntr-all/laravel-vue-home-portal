<?php

namespace App\Filters\Classes;

use App\Filters\Interfaces\FilterInterface;
use Illuminate\Database\Eloquent\Builder;

class MusicTagsFilter implements FilterInterface
{
    public function __construct(protected $tags)
    {
    }

    public function apply(Builder $query): Builder
    {
        $union = $this->tags['union'] ?? true;
        $tags = explode(',', $this->tags['tags']);

        if ($union) {
            foreach ($tags as $tag) {
                $query->whereRelation('tags', 'id', $tag);
            }
        } else {
            $query->whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('id', $tags);
            });
        }

        return $query;
    }
}
