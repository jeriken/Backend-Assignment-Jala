<?php

namespace App\Traits;

trait ApiResponser
{
    public function sendResponse($result, $message = "Success", $status = "Success")
    {
        $response = [
            'status' => $status,
            'message' => $message,
            'data'    => $result,
        ];

        return response()->json($response, 200);
    }

    public function sendError($message, $data = "No error", $code = 500, $status = "Not Found")
    {
        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
