<?php

namespace App\Models\Music;

use App\Models\Traits\HasDates;
use App\Models\Traits\HasFilters;
use App\Models\Traits\HasMusicTags;
use App\Models\Traits\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Music\Artist
 *
 * @property int $id
 * @property string $name
 * @property string|null $country
 * @property string|null $content
 * @property string|null $image
 * @property string $path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Music\Album> $albums
 * @property-read int|null $albums_count
 * @property-read string $full_image
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Music\MusicTag> $tags
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Music\Track> $tracks
 * @property-read int|null $tracks_count
 * @method static \Database\Factories\Music\ArtistFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Artist filter($filter)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Artist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Artist onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Artist query()
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Artist withoutTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Artist updateOrCreate($attributes, $values)
 * @mixin \Eloquent
 */
class Artist extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasDates;
    use HasMusicTags;
    use HasImage;
    use HasFilters;

    protected $table = 'music_artists';

    protected $guarded = [];

    public function albums(): HasMany
    {
        return $this->hasMany(Album::class, 'artist_id', 'id');
    }

    public function tracks(): HasManyThrough
    {
        return $this->hasManyThrough(Track::class, Album::class);
    }
}
