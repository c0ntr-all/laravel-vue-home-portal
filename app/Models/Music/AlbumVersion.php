<?php

namespace App\Models\Music;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Music\AlbumVersion
 *
 * @property int $id
 * @property int $album_id
 * @property int $version_type_id
 * @property string|null $description
 * @property string|null $year
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class AlbumVersion extends Model
{
    protected $table = 'music_album_versions';

    protected $guarded = [];

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class, 'album_id', 'id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(AlbumVersionType::class, 'version_type_id', 'id');
    }
}
