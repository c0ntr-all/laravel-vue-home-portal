<?php

namespace App\Models\Traits;

use App\Models\Music\Tag;
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
        return $this->morphToMany(Tag::class, 'tagable', 'music_tagables');
    }
}
