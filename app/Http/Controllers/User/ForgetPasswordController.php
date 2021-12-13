<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ForgetPasswordRequest;
use App\Http\Services\User\ForgetPasswordService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Response;

class ForgetPasswordController extends Controller
{
    public $service;

    public function __construct(ForgetPasswordService $service)
    {
        $this->service = $service;
    }

    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $service = $this->service->forgetPassword($request);
        return Response::successResponse($service, Lang::get('messages.forget_password_msg'));
    }

    public function resetPassword(ForgetPasswordRequest $request)
    {
        $service = $this->service->resetPassword($request);
        return Response::successResponse($service, 'تم تحديث كلمة المرور بنجاح');
    }

    public function checkCode(ForgetPasswordRequest $request)
    {
        $service = $this->service->checkCode($request);
        if (!$service) {
            return Response::errorResponse(['is_verified' => $service]);
        }
        return Response::successResponse(['is_verified' => $service]);
    }

    public function resendCode(ForgetPasswordRequest $request)
    {
        $service = $this->service->resend($request);
        return Response::successResponse($service, 'تم اعاده ارسال الكود');
    }
}
