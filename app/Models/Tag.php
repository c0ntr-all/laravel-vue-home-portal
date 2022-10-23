<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Music\Artist;
use App\Models\Music\Album;
use App\Models\Music\Track;
use App\Models\Traits\HasDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    use HasFactory;
    use HasDates;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'updated_at',
    ];

    public function artists(): MorphToMany
    {
        return $this->morphedByMany(Artist::class, 'tagable');
    }

    public function albums(): MorphToMany
    {
        return $this->morphedByMany(Album::class, 'tagable');
    }

    public function tracks(): MorphToMany
    {
        return $this->morphedByMany(Track::class, 'tagable');
    }

    public function getItems(): Collection
    {
        return Tag::all();
    }

    /**
     * Получить пользователя, создавшего тег
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
