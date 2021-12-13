<?php

namespace App\Http\Services\User;

use App\Models\User\PasswordReset;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ForgetPasswordService
{
    public function forgetPassword(Request $request)
    {
        $user = User::where('phone', $request->phone)
            ->where('active', 1)
            ->first();
        if (!$user) {
            throw new BadRequestHttpException(Lang::get('messages.phone_not_exists'));
        }
        PasswordReset::updateOrCreate([
            'phone' => $user->phone
        ], [
            //'token' => rand(10000, 99999)
            'token' => 5555
        ]);
        return ['phone' => $user->phone];
    }

    public function resetPassword(Request $request)
    {
        $passwordReset = PasswordReset::where('token', $request->code)
            ->where('phone', $request->phone)
            ->first();
        if (!$passwordReset) {
            throw new BadRequestHttpException(Lang::get('messages.verification_error')
            );
        }
        $user = User::select('id', 'name', 'phone', "image", "password", "active", "phone", "phone_verified_at")
            ->where('phone', $request->phone)->first();
        $user->update([
            'password'          => $request->password,
            'verification_code' => null,
            'phone_verified_at' => now(),
        ]);
        $passwordReset->delete();
        $user['token'] = $user->createToken('User Token')->accessToken;
        return $user;
    }

    public function checkCode(Request $request)
    {
        return PasswordReset::where('token', $request->code)
            ->where('phone', $request->phone)
            ->exists();
    }

    public function resend(Request $request)
    {
        $passwordReset = PasswordReset::where('phone', $request->phone)
            ->first();
        if (!$passwordReset) {
            throw new BadRequestHttpException(
                "لقد أدخلت رقم الهاتف او كود التفعيل غير صحيح."
            );
        }
        return ['phone' => $request->phone];
    }
}
