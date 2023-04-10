<?php

namespace App\Models\Music;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Playlist extends Model
{
    use HasFactory;

    public $table = 'music_playlists';

    public function tracks(): HasMany
    {
        return $this->hasMany(PlaylistItem::class);
    }
}
