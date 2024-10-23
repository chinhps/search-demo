<?php

namespace App\Traits;

trait BaseResponseTrait
{
    public function responseSuccess(array $data = [], $message = 'success', $status = 200)
    {
        return response()->json([
            'status' => $status,
            'error' => false,
            'message' => $message,
            'data' => $data,
        ], 200);
    }

    public function responseError($message = 'error', $status = 400, array $data = [])
    {
        return response()->json([
            'status' => $status,
            'error' => true,
            'message' => $message,
            'data' => $data,
        ], $status); // 200
    }
}
