<?php
namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
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

    /**
     * Ответ на основе наличия данных
     *
     * @param mixed $data
     * @return JsonResponse|Response
     */
    public function response(mixed $data): JsonResponse|Response
    {
        $result = match(true) {
            $data instanceof ResourceCollection => !$data->isEmpty(),
            $data instanceof JsonResource => $data,
            is_array($data) => !empty($data),
            default => null
        };

        return $result ?
            response()->json($data) :
            response()->noContent();
    }
}
