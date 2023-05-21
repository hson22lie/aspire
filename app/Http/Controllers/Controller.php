<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function successResponse($message, $data = null)
    {
        return response()->json([
            'status' => "success",
            'message' => ($message) ? $message : "success get response",
            'data' => $data
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    protected function failedResponse(\Exception $e)
    {
        $message = $e->getMessage();
        $code = ($e->getCode()) ? $e->getCode() : 500;
        return response()->json([
            'status' => 'failed',
            'message' => ($message) ? $message : "failed get response",
        ], $code);
    }
}
