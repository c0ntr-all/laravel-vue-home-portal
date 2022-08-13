<?php

namespace App\Models\Music;

use App\Models\Tag;
use App\Models\Traits\HasDates;
use App\Models\Traits\HasTags;
use App\Models\Traits\HasImage;
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
    use HasDates;
    use HasTags;
    use HasImage;

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
}
