<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    //response method
    public function sendResponse($data, $message)
    {
        $response = [
            'success' => true,
            'data' => $data,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    //error handling method
    public function sendError($error, $eorrorMessages = [])
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        if (!empty($eorrorMessages)) {
            $response['data'] = $eorrorMessages;
        }
        return response()->json($response, 404);
    }
}