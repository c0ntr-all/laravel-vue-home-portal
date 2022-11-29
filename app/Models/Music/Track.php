<?php

namespace App\Models\Music;

use App\Models\Traits\HasDates;
use App\Models\Traits\HasMusicTags;
use App\Models\Traits\HasRating;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Track extends Model
{
    use HasFactory;
    use HasDates;
    use HasMusicTags;
    use HasRating;

    protected $table = 'music_tracks';

    protected $fillable = [
        'album_id',
        'number',
        'name',
        'path_windows',
        'duration',
        'updated_at',
        'deleted_at'
    ];

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }
}
