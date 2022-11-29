<?php

namespace App\Models\Music;

use App\Models\Traits\HasDates;
use App\Models\Traits\HasMusicTags;
use App\Models\Traits\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin \Eloquent
 */
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
        'updated_at',
        'deleted_at'
    ];

    /**
     * Получает все альбомы исполнителя
     *
     * @return HasMany
     */
    public function albums(): HasMany
    {
        return $this->hasMany(Album::class, 'artist_id', 'id');
    }

    public static function getFiltered(array $filters = [])
    {
        return static::filter($filters, 'tags', 'tags', 'name')
                      ->cursorPaginate(12);
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
