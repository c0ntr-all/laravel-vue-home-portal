<?php

namespace App\Models\Music;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Music\MusicTag
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class MusicTagGroup extends Model
{
    protected $table = 'music_tag_groups';

    public function tagables(): HasMany
    {
        return $this->hasMany(MusicTagable::class, 'tag_group_id');
    }
}
