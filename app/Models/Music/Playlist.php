<?php

namespace App\Models\Music;

use App\Models\Traits\HasDates;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Playlist extends Model
{
    use HasFactory,
        HasDates;

    public $table = 'music_playlists';

    public function tracks(): HasMany
    {
        return $this->hasMany(PlaylistItem::class);
    }

    public function scopeUser(Builder $query, int $userid): void
    {
        $query->where('user_id', $userid);
    }
}
