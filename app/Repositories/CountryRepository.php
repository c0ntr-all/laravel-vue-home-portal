<?php

namespace App\Repositories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Collection;

class CountryRepository
{
    public function getCountries(): Collection
    {
        return Country::all();
    }
}
