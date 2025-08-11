<?php

namespace Modules\CentralApplication\Traits;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    protected function successResponse($data, $message = null, $code = 200)
    {
        if ($data instanceof ResourceCollection && $data->resource instanceof LengthAwarePaginator) {
            $pagination = $data->resource;

            return response()->json([
                'status'  => 'success',
                'message' => $message,
                'data'    => $data->response()->getData(true)['data'],
                'meta'    => [
                    'current_page' => $pagination->currentPage(),
                    'last_page'    => $pagination->lastPage(),
                    'per_page'     => $pagination->perPage(),
                    'total'        => $pagination->total(),
                ],
            ], $code);
        }

        // For non-paginated data this condition will apply
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    protected function errorResponse($message, $code = 400, $data = null)
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    protected function warningResponse($message, $code = 400, $data = null)
    {
        return response()->json([
            'status'  => 'warning',
            'message' => $message,
            'data'    => $data,
        ], $code);
    }
}
