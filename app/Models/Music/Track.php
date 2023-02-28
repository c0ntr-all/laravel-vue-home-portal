<?php

namespace App\Models\Music;

use App\Models\Traits\HasDates;
use App\Models\Traits\HasMusicTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;

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
