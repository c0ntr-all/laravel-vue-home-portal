<?php

namespace App\Http\Resources\Music\Tag;

use App\Models\Music\MusicTag;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TagTreeCollection extends ResourceCollection
{
    public static $wrap = '';
    public array $tags;

    public function toArray($request): array
    {
        return [
            'items' => [
                'common' => $this->prepareTags(),
                'secondary' => $this->prepareTags(false)
            ],
            'count' => $this->count()
        ];
    }

    private function prepareTags($common = true): array
    {
        $out = [];
        $this->collection->where('parent_id', 0)
                         ->where('common', $common)
                         ->each(function ($item) use (&$out) {
                             $out[] = $this->tree($item);
                         });

        return $out;
    }

    public function tree(MusicTag $tag): array
    {
        if ($tag->childrenCategories) {
            $item = [
                'id' => $tag->id,
                'label' => $tag->name,
                'type' => '',
                'slug' => $tag->slug,
                'content' => $tag->content,
                'common' => $tag->common,
                'parent_id' => $tag->parent_id,
                'createdAt' => $tag->created_at
            ];

            foreach ($tag->childrenCategories as $value) {
                $item['children'][] = $this->tree($value);
            }

            return $item;
        }

        return [
            'id' => $tag->id,
            'label' => $tag->name,
            'type' => '',
            'slug' => $tag->slug,
            'content' => $tag->content,
            'common' => $tag->common,
            'parent_id' => $tag->parent_id,
            'createdAt' => $tag->created_at
        ];
    }
}
