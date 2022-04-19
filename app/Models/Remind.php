<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Remind extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'datetime',
        'is_active',
        'created_at',
        'updated_at'
    ];

    public function getItems(): Collection
    {
        return Remind::all();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
