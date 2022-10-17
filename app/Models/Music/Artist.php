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

    public static function getFiltered(array $filters = []): Collection
    {
        return static::filter($filters, 'tag', 'tags', 'name')
                    ->when(array_key_exists('offset', $filters), function ($q) use ($filters) {
                        $q->offset($filters['offset'])->limit($filters['limit']);
                    })
                    ->get();
    }

    public function scopeFilter($query, array $filters, string $key, string $relation, string $column)
    {
        return $query->when(array_key_exists($key, $filters), function ($q) use ($filters, $relation, $column, $key) {
            $q->whereRelation($relation, $column, $filters[$key]);
        });
    }
}
