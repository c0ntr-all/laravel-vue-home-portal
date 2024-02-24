<?php

namespace App\Models\Music;

use App\Casts\TrackDurationCast;
use App\Models\Traits\HasDates;
use App\Models\Traits\HasFilters;
use App\Models\Traits\HasImage;
use App\Models\Traits\HasMusicTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Znck\Eloquent\Relations\BelongsToThrough;
use App\Helpers\ArrayHelper;

/**
 * App\Models\Music\Track
 *
 * @property int $id
 * @property int|null $album_id
 * @property int|null $number
 * @property string $name
 * @property int|null $cd
 * @property string|null $path
 * @property string|null $image
 * @property mixed $duration
 * @property int|null $bitrate
 * @property string|null $link
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Music\Album|null $album
 * @property-read string $full_image
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Music\Playlist> $playlists
 * @property-read int|null $playlists_count
 * @property-read \App\Models\Music\Rate|null $rate
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Music\MusicTag> $tags
 * @property-read int|null $tags_count
 * @method static \Database\Factories\Music\TrackFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Track filter($filter)
 * @method static \Illuminate\Database\Eloquent\Builder|Track newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Track newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Track onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Track onlyWeb()
 * @method static \Illuminate\Database\Eloquent\Builder|Track query()
 * @method static \Illuminate\Database\Eloquent\Builder|Track user($userId)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereAlbumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereBitrate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereCd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereRate($filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereTags($filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Track withoutTrashed()
 * @mixin \Eloquent
 */
class Track extends Model
{
    use SoftDeletes,
        HasFactory,
        HasDates,
        HasMusicTags,
        HasImage,
        HasFilters,
        \Znck\Eloquent\Traits\BelongsToThrough;

    protected $table = 'music_tracks';

    protected $guarded = [];

    protected $casts = [
        'duration' => TrackDurationCast::class
    ];

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    public function artist(): BelongsToThrough
    {
        return $this->belongsToThrough(
            Artist::class,
            Album::class,
            null,
            '',
            [Artist::class => 'artist_id', Album::class => 'album_id']
        );
    }

    public function playlists(): belongsToMany
    {
        return $this->belongsToMany(Playlist::class, 'music_playlists_tracks', 'track_id', 'playlist_id');
    }

    public function rate(): HasOne
    {
        return $this->hasOne(Rate::class);
    }

    public function scopeUser($query, $userId): void
    {
        $query->where('user_id', $userId);
    }

    public function scopeWhereTags($query, $filters)
    {
        $union = $filters['union'] ?? true;

        if (!empty($filters['tags'])) {
            $filters['tags'] = array_column($filters['tags'], 'value');
            if ($filters['type'] == 'hierarchical') {
                $hierarchyTags = $this->getTagsHierarchy($filters['tags']);
                $filters['tags'] = array_merge(...$hierarchyTags->toArray());
            }
        }

        if ($union) {
            foreach ($filters['tags'] as $filter) {
                $query->whereRelation('tags', 'id', $filter);
            }
        } else {
            $query->whereHas('tags', function ($query) use ($filters) {
                $query->whereIn('id', $filters['tags']);
            });
        }
    }

    public function scopeWhereRate($query, $filters)
    {
        $query->whereHas('rate', function ($query) use ($filters) {
            $query->whereIn('rate', $filters['rate']);
        });
    }

    public function scopeOnlyWeb($query)
    {
        $query->whereNotNull('link');
    }

    private function getTagsHierarchy($tags)
    {
        return MusicTag::whereIn('id', $tags)->get()->map(function($item) {
            $tagsList = ArrayHelper::flattenArray($item->childrenCategories->toArray());

            return array_column($tagsList, 'id');
        });
    }

    public static function filterWithCursor(array $filters = [])
    {
        $query = static::orderBy('created_at', 'DESC');

        if (!empty($filters['tags'])) {
            $query = $query->whereTags($filters);
        }

        if (!empty($filters['rate'])) {
            $query = $query->whereRate($filters);
        }

        if (!empty($filters['tracks'])) {
            $query = $query->onlyWeb();
        }

        return $query->cursorPaginate(50);
    }
}
