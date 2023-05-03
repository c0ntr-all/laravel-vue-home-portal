<?php

namespace App\Models\Music;

use App\Casts\TrackDurationCast;
use App\Models\Traits\HasDates;
use App\Models\Traits\HasImage;
use App\Models\Traits\HasMusicTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Znck\Eloquent\Relations\BelongsToThrough;

class Track extends Model
{
    use HasFactory;
    use HasDates;
    use HasMusicTags;
    use HasImage;
    use \Znck\Eloquent\Traits\BelongsToThrough;

    protected $table = 'music_tracks';

    protected $fillable = [
        'album_id',
        'number',
        'name',
        'path',
        'image',
        'duration',
        'bitrate',
        'updated_at',
        'deleted_at'
    ];

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

    private function getTagsHierarchy($tags)
    {
        return Tag::whereIn('id', $tags)->get()->map(function($item) {
            $tagsList = $this->normalizeArray($item->childrenCategories->toArray());

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

        return $query->cursorPaginate(50);
    }

    /**
     * Из рекурсивно вложенного массива делает обычный массив
     *
     * @param array $array
     * @return array
     */
    public function normalizeArray(array $array = []): array
    {
        static $out = [];

        foreach ($array as $subArray) {
            if (!empty($subArray['children'])) {
                $arrayToAdd = $subArray;
                unset($arrayToAdd['children']);

                $out[] = $arrayToAdd;
                $this->normalizeArray($subArray['children']);
            } else {
                if (isset($subArray['children'])) {
                    unset($subArray['children']);
                }

                $out[] = $subArray;
            }
        }

        return $out;
    }
}
