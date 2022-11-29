<?php

namespace App\Http\Resources\Music\Tag;

use App\Models\Music\Tag;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TagTreeCollection extends ResourceCollection
{
    public static $wrap = '';
    public array $tags;

    public function toArray($request): array
    {
        return [
            'tags' => $this->prepareTags(),
            'tagsCount' => $this->count()
        ];
    }

    private function prepareTags(): array
    {
        $this->collection->where('parent_id', 0)->each(function($item) {
            $this->tags[] = $this->tree($item);
        });

        return $this->tags;
    }

    public function tree(Tag $tag): array
    {
        if ($tag->childrenCategories) {
            $item = [
                'id' => $tag->id,
                'label' => $tag->name,
                'type' => '',
                'slug' => $tag->slug,
                'content' => $tag->content,
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
            'parent_id' => $tag->parent_id,
            'createdAt' => $tag->created_at
        ];
    }
}
