<?php

namespace App\Models\Music;

use App\Models\Traits\HasDates;
use App\Models\Traits\HasMusicTags;
use App\Models\Traits\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Helpers\ArrayHelper;

class Artist extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasDates;
    use HasMusicTags;
    use HasImage;

    protected $table = 'music_artists';

    protected $fillable = [
        'user_id',
        'name',
        'content',
        'image',
        'path',
        'updated_at',
        'deleted_at'
    ];

    public function albums(): HasMany
    {
        return $this->hasMany(Album::class, 'artist_id', 'id');
    }

    public function tracks(): HasManyThrough
    {
        return $this->hasManyThrough(Track::class, Album::class);
    }

    /**
     * Формирует запрос фиьтрации по параметрам в рамках конкретного отношения
     * Фильтры:
     * array tags - Массив тегов.
     * string type - Strict/Hierarchical. Поиск конкретного тега или также всех его производных.
     * bool union - Искать все теги в рамках одного исполнителя или важно присутствие хотя бы одного тега.
     *
     * @param $query
     * @param array $filters
     * @param string $key
     * @param string $relation
     * @param string $column
     * @return mixed
     */
    public function scopeFilter($query, array $filters, string $key, string $relation, string $column)
    {
        if (!empty($filters['tags']) && $filters['type'] == 'hierarchical') {
            $result = MusicTag::whereIn('id', $filters['tags'])->get()->map(function($item) {
                $tagsList = ArrayHelper::normalizeArray($item->childrenCategories->toArray());
                return array_column($tagsList, 'id');
            });
            $filters['tags'] = array_merge(...$result->toArray());
        }

        return $query->when(array_key_exists($key, $filters), function ($q) use ($filters, $relation, $column, $key) {
            $union = $filters['union'];

            if ($union) {
                foreach ($filters[$key] as $filter) {
                    $q->whereRelation($relation, $column, $filter);
                }
            } else {
                $q->whereHas('tags', function ($q) use ($column, $filters, $key) {
                    $q->whereIn($column, $filters[$key]);
                });
            }
        });
    }
}
