<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AuthRequest;
use App\Http\Services\User\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AdminAuthController extends Controller
{

    private $authService;
    private $userService;

    public function __construct()
    {
        $this->middleware('auth:api')->except('login');
        $this->authService = new AuthService();
    }

    public function login(Request $request)
    {
        $service = $this->authService->login($request,[1,2]);
        return Response::successResponse($service, 'تم تسجيل الدخول بنجاح.');
    }

//    public function edit(AuthRequest $request)
//    {
//        $service = $this->userService->editAdminProfile($request);
//        return Response::successResponse($service, 'تم التعدبل.');
//    }
//
//    public function profile(Request $request)
//    {
//        request()->merge(['user_id' => Auth::id()]);
//        $service = $this->userService->profile($request);
//        return Response::successResponse($service);
//    }
}
