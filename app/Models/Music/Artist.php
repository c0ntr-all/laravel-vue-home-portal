<?php

namespace App\Models\Music;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Artist extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'music_artists';

    protected $fillable = [
        'user_id',
        'name',
        'content',
        'image',
        'updated_at',
        'deleted_at'
    ];

    public function getItems()
    {
        return Artist::all();
    }

    public function albums(): HasMany
    {
        return $this->hasMany(Album::class, 'artist_id', 'id');
    }
}
