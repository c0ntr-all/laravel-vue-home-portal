<?php

namespace App\Models\Music;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Track extends Model
{
    use HasFactory;

    private $table = 'music_albums';

    protected $fillable = [
        'album_id',
        'name',
        'image',
        'updated_at',
        'deleted_at'
    ];

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }
}
