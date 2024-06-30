<?php

namespace App\Models\Music;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

/**
 * App\Models\Music\MusicTag
 *
 * @property int $tag_id
 * @property int $tagable_id
 * @property string $tagable_type
 * @property int $tag_group_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class MusicTagable extends MorphPivot
{
    protected $table = 'music_tagables';

    public function group(): BelongsTo
    {
        return $this->belongsTo(MusicTagGroup::class, 'tag_group_id');
    }
}
