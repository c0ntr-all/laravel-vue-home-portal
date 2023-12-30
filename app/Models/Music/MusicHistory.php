<?php

namespace App\Models\Music;

use App\Models\Traits\HasDates;
use App\Models\Traits\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * App\Models\Music\MusicHistory
 *
 * @property int $user_id
 * @property int $track_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Music\Track $track
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\Music\MusicHistoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|MusicHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MusicHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MusicHistory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MusicHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|MusicHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MusicHistory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MusicHistory whereTrackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MusicHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MusicHistory whereUser(string $userId)
 * @method static \Illuminate\Database\Eloquent\Builder|MusicHistory whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MusicHistory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MusicHistory withoutTrashed()
 * @mixin \Eloquent
 */
class MusicHistory extends Model
{
    use HasFactory,
        HasDates,
        HasUser,
        SoftDeletes;

    protected $table = 'music_history';

    protected $fillable = [
        'user_id',
        'track_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function track(): BelongsTo
    {
        return $this->belongsTo(Track::class);
    }
}
