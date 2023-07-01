<?php

namespace App\Filters\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface FilterInterface
{
    public function apply(Builder $query): Builder;
}
