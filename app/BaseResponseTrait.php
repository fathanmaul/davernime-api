<?php

namespace App;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

trait BaseResponseTrait
{
    protected function resolveSuccessResponse(?string $message = "Success", $data = [], Response | int $status = Response::HTTP_OK): JsonResponse
    {
        $response = ['message' => $message];

        if (!empty($data)) {
            if ($data instanceof LengthAwarePaginator) {
                $response = array_merge($response, $data->toArray());
            } else {
                $response['data'] = $data;
            }
        }
        return response()->json($response, $status);
    }

    protected function resolveErrorResponse(?array $errors = [], Response | int $status = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        $response = ['message' => "Error"];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }
}
