<?php


namespace App\Http\Services\User;


use App\Exceptions\NoPermissionException;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AuthService
{
    public function login(Request $request, $types = [2, 3, 4, 5])
    {
        $user = User::select('id', 'name', 'phone', "image", "password",
            "active", "phone", "phone_verified_at", 'type', "project_id")
            ->where('phone', $request->phone)
            ->whereIn('type', $types)
            ->first();
        if (!$user) {
            throw new BadRequestHttpException(Lang::get('messages.invalid_username_or_password'));
        }
        $user->validateForPassportPasswordGrant($request->password);
        $user->checkAccountVerification();
        $user->checkActivation();
        $user['is_attended'] = $user->checkUserAttendance();
        $user['token'] = $user->createToken('User_Token')->accessToken;
        return $user;
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->AauthAcessToken()->delete();
        }
        return ['is_success' => true];
    }

    public function adminProfile()
    {
        return User::select('id', 'name', 'phone', 'type')
            ->firstOrFail();
    }

    public function editAdminProfile(Request $request)
    {
        $user = Auth::user();
        if ($request->password) {
            $user->update([
                'password'        => $request->password,
                'pass_changed_at' => now()
            ]);
        }
        $user->update($request->only(['name', 'email']));
        return $user;
    }
}
