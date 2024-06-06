<?php

namespace App\Models\Music;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Music\AlbumVersionType
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class AlbumVersionType extends Model
{
    protected $table = 'music_album_version_types';

    protected $guarded = [];

    public function albumVersions(): HasMany
    {
        return $this->hasMany(AlbumVersion::class, 'version_type_id', 'id');
    }
}
