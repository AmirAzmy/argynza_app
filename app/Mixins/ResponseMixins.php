<?php


namespace App\Mixins;


use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;

class ResponseMixins
{
    public function successResponse()
    {
        return function ($data, $message = null, $code = Response::HTTP_OK) {
            return response()->json([
                'code'    => $code,
                'status'  => 1,
                'errors'  => null,
                'message' => !$message ? Lang::get('messages.success') : $message,
                'data'    => $data
            ], $code);
        };
    }

    public function errorResponse()
    {
        return function ($data, $message = null, $code = Response::HTTP_BAD_REQUEST) {
            return response()->json([
                'code'    => $code,
                'status'  => 0,
                'errors'  => $data,
                'message' => !$message ? Lang::get('messages.fail') : $message,
                'data'    => null
            ], $code);
        };
    }
}
