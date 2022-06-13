<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FolderService;

class FolderController extends Controller
{
    public function __construct(FolderService $folderService)
    {
        $this->folderService = $folderService;
    }

    public function index()
    {
        return $this->folderService->showDisks();
    }
}
