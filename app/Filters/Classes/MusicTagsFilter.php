<?php

namespace App\Filters\Classes;

use App\Filters\Interfaces\FilterInterface;
use App\Models\Music\MusicTag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class MusicTagsFilter implements FilterInterface
{
    public function __construct(protected $tags)
    {
    }

    public function apply(Builder $query): Builder
    {
        $type = $this->tags['type'] ?? false;
        $tags = explode(',', $this->tags['tags']);

        // If hierarchy mode is selected, we'll take all children tags
        if ($type == 'hierarchical') {
            // Adding a method to collections to flatten all nested child collections
            Collection::macro('flattenDescendants', function () {
                return $this->flatMap(function ($item) {
                    $children = $item['descendants'] ?? [];
                    unset($item['descendants']);

                    return collect([$item])->concat(collect($children)->flattenDescendants());
                });
            });
            $tags = MusicTag::whereIn('id', $tags)
                            ->with('descendants')
                            ->get()
                            ->flattenDescendants()
                            ->pluck('id')
                            ->flatten()
                            ->sort()
                            ->values()
                            ->toArray();
        }

        if ($type == 'union') {
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
