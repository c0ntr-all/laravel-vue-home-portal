<?php

namespace App\Models\Music;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Music\AlbumDisc
 *
 * @property int $id
 * @property int $album_version_id
 * @property int $number
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class AlbumDisc extends Model
{
    protected $table = 'music_album_discs';

    protected $guarded = [];

    public function version(): BelongsTo
    {
        return $this->belongsTo(AlbumVersion::class, 'album_version_id', 'id');
    }

    public function tracks(): HasMany
    {
        return $this->hasMany(Track::class, 'disc_id', 'id');
    }
}
