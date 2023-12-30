<?php

namespace App\Models\Music;

use App\Models\Traits\HasDates;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Music\Playlist
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Music\Track> $tracks
 * @property-read int|null $tracks_count
 * @method static \Database\Factories\Music\PlaylistFactory factory($count = null, $state = [])
 * @method static Builder|Playlist newModelQuery()
 * @method static Builder|Playlist newQuery()
 * @method static Builder|Playlist query()
 * @method static Builder|Playlist user(int $userid)
 * @method static Builder|Playlist whereContent($value)
 * @method static Builder|Playlist whereCreatedAt($value)
 * @method static Builder|Playlist whereId($value)
 * @method static Builder|Playlist whereName($value)
 * @method static Builder|Playlist whereUpdatedAt($value)
 * @method static Builder|Playlist whereUserId($value)
 * @mixin \Eloquent
 */
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
