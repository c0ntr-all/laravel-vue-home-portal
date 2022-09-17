<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    public const DEFAULT_RATING = 3;
    public const MIN_RATING = 1;
    public const MAX_RATING = 5;

    protected $fillable = [
        'rating',
        'user_id',
        'ratingable_id',
        'ratingable_type',
    ];

    public function ratingable()
    {
        return $this->morphTo();
    }
}
