<?php

namespace App\Models\Music;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PlaylistItem extends Model
{
    use HasFactory;

    public $table = 'music_playlists_items';

    public function playlists(): belongsToMany
    {
        return $this->belongsToMany(Playlist::class);
    }
}
