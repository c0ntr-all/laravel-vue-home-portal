<?php

namespace App\Models\Music;

use App\Models\Traits\HasDates;
use App\Models\Traits\HasTags;
use App\Models\Traits\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @mixin \Eloquent
 */
class Artist extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasDates;
    use HasTags;
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

    public function scopeFilter($query, array $filters, string $key, string $relation, string $column)
    {
        return $query->when(array_key_exists($key, $filters), function ($q) use ($filters, $relation, $column, $key) {
            foreach ($filters[$key] as $filter) {
                $q->whereRelation($relation, $column, $filter);
            }
        });
    }
}
