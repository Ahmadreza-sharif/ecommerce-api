<?php

namespace App\Http\Controllers\api\traits;

trait ApiResponder
{
    public function sendSuccess($data = null, $message)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ];
        return response()->json($response, 200);
    }

    public function sendError($data = null ,$message, $code = 404)
    {
        $response = [
            'success' => false,
            'messages' => $message,
            'data' => $data,
        ];
        return response()->json($response, $code);
    }


}
