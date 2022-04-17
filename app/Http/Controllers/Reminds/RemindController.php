<?php

namespace App\Http\Controllers\Reminds;

use App\Http\Controllers\Controller;
use App\Models\Remind;
use App\Http\Resources\RemindCollection;
use App\Http\Requests\Reminds\IndexRequest;

class RemindController extends Controller
{
    protected $reminds;

    public function __construct(Remind $reminds)
    {
        $this->reminds = $reminds;
    }

    public function index(IndexRequest $request): RemindCollection
    {
        return new RemindCollection($this->reminds->getitems());
    }
}
