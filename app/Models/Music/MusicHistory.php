<?php

namespace App\Models\Music;

use App\Models\Traits\HasDates;
use App\Models\Traits\HasMusicTags;
use App\Models\Traits\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class MusicHistory extends Model
{
    use HasFactory,
        HasDates,
        HasUser,
        SoftDeletes;

    protected $table = 'music_history';

    protected $fillable = [
        'user_id',
        'track_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function track(): BelongsTo
    {
        return $this->belongsTo(Track::class);
    }

    public static function getHistory($user): Collection
    {
        return MusicHistory::whereUser($user)->with('track.tags')->get();
    }
}
