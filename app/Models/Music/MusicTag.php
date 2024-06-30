<?php

namespace App\Models\Music;

use App\Models\Traits\HasDates;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * App\Models\Music\MusicTag
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $name
 * @property string $slug
 * @property string|null $content
 * @property bool $is_base
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, \App\Models\Music\Album> $albums
 * @property-read int|null $albums_count
 * @property-read Collection<int, \App\Models\Music\Artist> $artists
 * @property-read int|null $artists_count
 * @property-read Collection<int, MusicTag> $children
 * @property-read int|null $children_count
 * @property-read MusicTag|null $parent
 * @property-read Collection<int, \App\Models\Music\Track> $tracks
 * @property-read int|null $tracks_count
 * @method static \Database\Factories\Music\MusicTagFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|MusicTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MusicTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MusicTag query()
 * @method static \Illuminate\Database\Eloquent\Builder|MusicTag whereCommon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MusicTag whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MusicTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MusicTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MusicTag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MusicTag whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MusicTag whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MusicTag whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MusicTag extends Model
{
    use HasFactory;
    use HasDates;

    protected $table ='music_tags';

    protected $guarded = [];

    protected $casts = [
        'is_base' => 'boolean'
    ];

    public function artists(): MorphToMany
    {
        return $this->morphedByMany(Artist::class, 'tagable')
                    ->withTimestamps()
                    ->using(MusicTagable::class);
    }

    public function albums(): MorphToMany
    {
        return $this->morphedByMany(Album::class, 'tagable')
                    ->withTimestamps()
                    ->using(MusicTagable::class);
    }

    public function tracks(): MorphToMany
    {
        return $this->morphedByMany(Track::class, 'tagable')
                    ->withTimestamps()
                    ->using(MusicTagable::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'id', 'parent_id');
    }

    /**
     * Getting unlimited nesting of child tags
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id', 'id')->with('children');
    }
}
