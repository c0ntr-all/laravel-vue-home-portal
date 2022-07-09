<?php

namespace App\Models\Music;

use App\Models\Traits\HasDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Tag;

class Artist extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasDates;

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
     * Аксессор на получение абсолютного пути до изображения
     *
     * @return string
     */
    public function getFullImageAttribute(): string
    {
        return env('APP_URL') . '/storage/' . $this->image;
    }


    /**
     * Получает все альбомы исполнителя
     *
     * @return HasMany
     */
    public function albums(): HasMany
    {
        return $this->hasMany(Album::class, 'artist_id', 'id');
    }

    /**
     * Получает все теги исполнителя
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function tags(): MorphMany
    {
        return $this->morphMany(Tag::class, 'tagable');
    }
}
