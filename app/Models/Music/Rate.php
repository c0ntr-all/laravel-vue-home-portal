<?php

namespace App\Models\Music;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $table = 'music_rate';

    public const DEFAULT_RATE = 3;
    public const MIN_RATE = 1;
    public const MAX_RATE = 4;

    protected $fillable = [
        'rating',
        'user_id',
        'ratingable_id',
        'ratingable_type',
    ];
}
