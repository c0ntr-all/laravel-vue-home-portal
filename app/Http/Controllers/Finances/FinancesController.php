<?php

namespace App\Http\Controllers\Finances;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finances\IndexRequest;
use App\Http\Resources\Finances\FinancesCollection;
use App\Http\Resources\Finances\FinancesResource;
use App\Models\Finances\Finances;
use App\Models\User;
use App\Services\FinancesService;

class FinancesController extends Controller
{
    public function __construct(Finances $finances, FinancesService $financesService, User $user)
    {
        $this->finances = $finances;
        $this->financesService = $financesService;
        $this->user = $user;
    }

    public function index(IndexRequest $request): FinancesCollection
    {
        return new FinancesCollection($this->finances->getItems());
    }

    public function show(Finances $finances): FinancesResource
    {
        return $this->financesResponse($finances);
    }

    protected function financesResponse(Finances $finances): FinancesResource
    {
        return new FinancesResource($finances->load('user', 'shop'));
    }
}
