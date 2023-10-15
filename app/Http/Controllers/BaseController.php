<?php
namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller as Controller;

abstract class BaseController extends Controller
{
    public function sendResponse($data, $message = 'Data loaded successfully!'): Response
    {
        $response = [
            'success' => true,
            'data'    => $data,
            'message' => $message,
        ];

        return response($response, 200);
    }

    public function sendError($message, $code = 500): Response
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        return response($response, $code);
    }
}
