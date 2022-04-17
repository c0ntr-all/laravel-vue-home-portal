<?php

namespace App\Http\Controllers\Reminds;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Remind;
use App\Http\Resources\RemindCollection;
use App\Http\Resources\RemindResource;
use App\Http\Requests\Reminds\IndexRequest;

class RemindController extends Controller
{
    protected $reminds;

    public function __construct($reminds)
    {
        $this->reminds = $reminds;
    }

    public function index(IndexRequest $request): RemindCollection
    {
        return new RemindCollection($this->reminds->getitems());
    }
}
