<?php

namespace App\Models;

use App\Models\Music\MusicHistory;
use App\Models\Music\Playlist;
use App\Models\Reminds\Remind;
use App\Models\Reminds\RemindGroup;
use App\Models\Tasks\TaskList;
use App\Models\Traits\HasDates;
use App\Models\Widgets\WidgetPlacement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $image
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, MusicHistory> $musicHistory
 * @property-read int|null $music_history_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Playlist> $playlists
 * @property-read int|null $playlists_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Remind> $reminds
 * @property-read int|null $reminds_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, RemindGroup> $remindsGroups
 * @property-read int|null $reminds_groups_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Setting> $settings
 * @property-read int|null $settings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TaskList> $taskLists
 * @property-read int|null $task_lists_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, WidgetPlacement> $widgets
 * @property-read int|null $widgets_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use HasDates;

    protected $fillable = ['username', 'email', 'password', 'bio', 'images'];

    protected $visible = ['username', 'email', 'bio', 'images'];

    public function getRouteKeyName(): string
    {
        return 'username';
    }

    public function taskLists(): HasMany
    {
        return $this->hasMany(TaskList::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class);
    }

    public function reminds(): HasMany
    {
        return $this->hasMany(Remind::class, 'user_id', 'id');
    }

    public function remindsGroups(): HasMany
    {
        return $this->hasMany(RemindGroup::class, 'user_id', 'id');
    }

    public function widgets(): HasMany
    {
        return $this->hasMany(WidgetPlacement::class);
    }

    public function playlists(): HasMany
    {
        return $this->hasMany(Playlist::class);
    }

    public function musicHistory(): HasMany
    {
        return $this->hasMany(MusicHistory::class)->with('track.tags');
    }

    public function setPasswordAttribute(string $password): void
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
