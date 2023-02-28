<?php

namespace App\Models\Music;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $table = 'music_rate';

    public const DEFAULT_RATE = 3;
    public const MIN_RATE = 0;
    public const MAX_RATE = 4;

    protected $fillable = [
        'rate',
        'user_id',
        'track_id',
    ];
}
