<?php

namespace App\Models\Traits;

use App\Models\Music\MusicTag;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasMusicTags
{
    /**
     * Удаляет связь между моделью и тегом, если модель была удалена
     */
    protected static function bootHasMusicTags()
    {
        static::deleting(fn($item) => $item->tags()->detach());
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(MusicTag::class, 'tagable', 'music_tagables', 'tagable_id', 'tag_id');
    }
}
