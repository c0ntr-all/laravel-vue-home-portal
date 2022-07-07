<?php

namespace App\Models\Music;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Album extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'music_albums';

    protected $fillable = [
        'user_id',
        'artist_id',
        'name',
        'year',
        'content',
        'image',
        'updated_at',
        'deleted_at'
    ];

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class, 'artist_id', 'id');
    }

    public function tracks(): HasMany
    {
        return $this->hasMany(Track::class, 'album_id', 'id');
    }

    /**
     * Получает все теги модели
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function tags(): MorphMany
    {
        return $this->morphMany(Tag::class, 'tagable');
    }

    public function getFullImageAttribute(): string
    {
        return env('APP_URL') . '/storage/' . $this->image;
    }
}
