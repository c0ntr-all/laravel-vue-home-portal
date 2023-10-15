<?php

namespace App\Filters;

use App\Filters\Interfaces\FilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class Filter extends Collection
{
    public function __construct($items = [])
    {
        static::validate($items);

        return parent::__construct($items);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function apply(Builder $query): Builder
    {
        $this->each(fn ($filter) => $filter->apply($query));

        return $query;
    }

    /**
     * @param $items
     * @return void
     * @throws \Throwable
     */
    private function validate($items)
    {
        collect($items)->each(function ($item) {
            throw_unless(
                $item instanceof FilterInterface,
                new \Exception("Filter must implement FilterInterface")
            );
        });
    }
}
