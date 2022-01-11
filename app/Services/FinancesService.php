<?php

namespace App\Services;

use App\Models\Finances\Finances;
use App\Models\Finances\FinancesShop;

class FinancesService
{
    protected $finances;
    protected $financesShop;

    public function __construct(Finances $finances, FinancesShop $financesShop)
    {
        $this->finances = $finances;
        $this->financesShop = $financesShop;
    }
}
