<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasFilters
{
    /**
     * @param Builder $query
     * @param $filters
     * @return Builder
     */
    public function scopeFilter(Builder $query, $filter): Builder
    {
        return $filter !== null ? $filter->apply($query) : $query;
    }
}
