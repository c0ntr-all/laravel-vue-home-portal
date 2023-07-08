<?php

namespace App\Models;

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
