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
        $type = $this->tags['type'] ?? 'strict';
        $union = $this->tags['union'] ?? false;
        $tags = $this->tags['tags'];

        // If hierarchy mode is selected, we'll take all children tags
        if ($type == 'hierarchical') {
            // Adding a method to collections to flatten all nested child collections
            Collection::macro('flattenChildren', function () {
                return $this->flatMap(function ($item) {
                    $children = $item['children'] ?? [];
                    unset($item['children']);

                    return collect([$item])->concat(collect($children)->flattenChildren());
                });
            });
            $tags = MusicTag::whereIn('id', $tags)
                            ->with('children')
                            ->get()
                            ->flattenChildren()
                            ->pluck('id')
                            ->flatten()
                            ->sort()
                            ->values()
                            ->toArray();
        }

        if ($union && $type == 'strict') {
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
