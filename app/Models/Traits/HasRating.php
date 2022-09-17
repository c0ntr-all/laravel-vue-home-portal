<?php

namespace App\Models\Traits;

use App\Models\Content\Rating;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasRating
{
    public function ratings()
    {
        return $this->morphMany(Rating::class, 'ratingable');
    }
}
