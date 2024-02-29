<?php declare(strict_types=1);

namespace App\Repositories;

use App\Models\Music\MusicTag;
use Illuminate\Database\Eloquent\Collection;

class TagRepository
{
    public function getTags(): Collection
    {
        return MusicTag::orderBy('name')->get();
    }

    public function getTagsTree(): Collection
    {
        return MusicTag::with('children')
                       ->whereNull('parent_id')
                       ->get();
    }
}
