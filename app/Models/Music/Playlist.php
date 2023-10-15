<?php

namespace App\Models\Music;

use App\Models\Traits\HasDates;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Playlist extends Model
{
    use HasFactory,
        HasDates;

    public $fillable = [
        'user_id',
        'image',
        'name',
        'content'
    ];

    public $table = 'music_playlists';

    public function tracks(): belongsToMany
    {
        return $this->belongsToMany(Track::class, 'music_playlists_tracks', 'playlist_id', 'track_id');
    }

    public function scopeUser(Builder $query, int $userid): void
    {
        $query->where('user_id', $userid);
    }
}
