<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tag',
        'updated_at',
    ];

    /**
     * Получить родительскую модель (Artist, Album и т.д.), к которой относится комментарий.
     *
     * @return MorphTo
     */
    public function tagable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Получить пользователя, создавшего тег
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
