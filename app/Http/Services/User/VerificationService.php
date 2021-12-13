<?php

namespace App\Http\Services\User;

use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class VerificationService
{

    /**
     * confirm verification code
     *
     * @param  Request  $request
     * @return mixed
     */
    public function confirm(Request $request)
    {
        $user = User::select('id', 'name', 'phone', "image", "password", "active", "phone", "phone_verified_at")
            ->where('verification_code', $request->code)
            ->where('phone', $request->phone)
            ->where('active', 1)
            ->first();
        if (!$user) {
            throw new BadRequestHttpException(Lang::get('messages.verification_error'));
        }
        if ($user->verified_code == $request->code) {
            throw new BadRequestHttpException(Lang::get('messages.verification_error'));
        }
        $user->update([
            'verification_code' => null,
            'phone_verified_at' => now(),
        ]);
        $user['token'] = $user->createToken('User_Token')->accessToken;
        return $user;
    }

    /**
     * resend verification code
     *
     * @param  Request  $request
     * @return mixed
     */
    public function resendCode(Request $request)
    {
        $user = User::select('id', 'name', 'phone', "image", "password", "active", "phone", "phone_verified_at")
            ->where('phone', $request->phone)
            ->where('active', 1)
            ->where('verification_code', '<>', null)
            ->where('phone_verified_at', null)
            ->first();
        if (!$user) {
            throw new BadRequestHttpException('لا يمكن اعاده ارسال الرمز لان حساب موثق بالفعل');
        }
        $user->update(['verification_code' => 0]);
        return [
            'phone' => $user->phone
        ];
    }

    /**
     * check verification code
     *
     * @param  Request  $request
     * @return boolean
     */
    public function checkCode(Request $request)
    {
        return User::where('phone', $request->phone)
            ->where('active', 1)
            ->where('verification_code', $request->code)
            ->exists();
    }
}
