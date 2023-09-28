<?php

namespace App\Helpers;

class Helper
{
    public static function apiRes($message, $data = [], $status = true, $code = 200)
    {
        return response()->json([
            'success' => $status,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
