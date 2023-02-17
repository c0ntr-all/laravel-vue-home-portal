<?php

namespace App\Models\Music;

use App\Models\Traits\HasDates;
use App\Models\Traits\HasMusicTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Track extends Model
{
    use HasFactory;
    use HasDates;
    use HasMusicTags;

    protected $table = 'music_tracks';

    protected $fillable = [
        'album_id',
        'number',
        'name',
        'path_windows',
        'duration',
        'bitrate',
        'updated_at',
        'deleted_at'
    ];

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    public function rate(): HasOne
    {
        return $this->hasOne(Rate::class);
    }

    public function scopeUser($query, $userId): void
    {
        $query->where('user_id', $userId);
    }
}
