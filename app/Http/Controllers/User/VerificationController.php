<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\VerificationRequest;
use Illuminate\Http\Request;
use App\Http\Services\User\VerificationService;
use Illuminate\Support\Facades\Response;

class VerificationController extends Controller
{
    private $service;

    public function __construct(VerificationService $service)
    {
        $this->service = $service;
    }

    public function confirm(VerificationRequest $request)
    {
        $service = $this->service->confirm($request);
        return Response::successResponse($service);
    }

    public function resend(VerificationRequest $request)
    {
        $service = $this->service->resendCode($request);
        return Response::successResponse($service);
    }

    public function checkCode(VerificationRequest $request)
    {
        $service = $this->service->checkCode($request);
        if (!$service) {
            return Response::errorResponse(['is_verified' => $service]);
        }
        return Response::successResponse(['is_verified' => $service]);
    }

}
