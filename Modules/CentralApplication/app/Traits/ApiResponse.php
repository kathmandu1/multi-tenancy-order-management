<?php

namespace Modules\CentralApplication\Traits;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{

    /**
     * @OA\Schema(
     * schema="PaginationSchema",
     * title="Pagination Schema",
     * description="A schema for paginated responses.",
     * @OA\Property(
     * property="current_page",
     * type="integer",
     * format="int32",
     * description="The current page number.",
     * example=1
     * ),
     * @OA\Property(
     * property="last_page",
     * type="integer",
     * format="int32",
     * description="The last page number.",
     * example=10
     * ),
     * @OA\Property(
     * property="per_page",
     * type="integer",
     * format="int32",
     * description="The number of items per page.",
     * example=10
     * ),
     * @OA\Property(
     * property="total",
     * type="integer",
     * format="int32",
     * description="The total number of items.",
     * example=100
     * )
     * )
     */
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
