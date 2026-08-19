<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class BaseApiController extends Controller
{
    /**
     * Успешный ответ
     */
    protected function success($data = [], $message = 'Success')
    {
        return response()->json([
            'error' => false,
            'message' => $message,
            'data' => $data
        ]);
    }

    /**
     * Ошибка
     */
    protected function error($message = 'Error', $code = 400)
    {
        return response()->json([
            'error' => true,
            'message' => $message
        ], $code);
    }
}