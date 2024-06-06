<?php

namespace App\Models\Music;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Music\AlbumType
 *
 * @property int $id
 * @property int $album_id
 * @property int $version_type_id
 * @property string|null $description
 * @property string|null $year
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class AlbumType extends Model
{
    protected $table = 'music_album_types';

    protected $guarded = [];

    public function albums(): HasMany
    {
        return $this->hasMany(Album::class, 'album_id', 'id');
    }
}
