<?php

namespace App\Services;

use App\Models\Finances\Finances;

class FinancesService
{
    protected $finances;

    public function __construct(Finances $finances)
    {
        $this->finances = $finances;
    }
}
