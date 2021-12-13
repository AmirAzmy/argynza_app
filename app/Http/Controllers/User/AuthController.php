<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AuthRequest;
use App\Http\Services\User\AuthService;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(AuthRequest $request)
    {
        $service = $this->authService->login($request);
        return Response::successResponse($service);
    }

    public function logout()
    {
        $service = $this->authService->logout();
        return Response::successResponse($service);
    }
}
