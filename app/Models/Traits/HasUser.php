<?php

namespace App\Models\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

trait HasUser
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope('user', function ($builder) {
            $builder->where(static::make()->getTable() . '.user_id', '=', auth()->user()->id);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function scopeWhereUser(Builder $query, string $userId): void
    {
        $query->where('user_id', $userId);
    }
}
