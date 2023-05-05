<?php

namespace App\Models\Music;

use App\Models\Traits\HasDates;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class MusicTag extends Model
{
    use HasFactory;
    use HasDates;

    protected $table ='music_tags';

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'content',
        'common',
        'updated_at',
    ];

    protected $casts = [
        'common' => 'boolean'
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
        return $this->orderBy('name')->get();
    }

    /**
     * Получение дочерних тегов
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id', 'id') ;
    }

    /**
     * Получение неограниченной вложенности дочерних тегов
     *
     * @return HasMany
     */
    public function childrenCategories(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id', 'id')->with('children');
    }
}
