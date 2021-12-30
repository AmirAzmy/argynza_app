<?php

namespace App\Http\Services\User;

use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserServices
{
    public function create(Request $request)
    {
        return User::create($request->only([
            'name', 'phone', 'password', 'project_id',
            "image", "active", "phone"
        ]));
    }

    public function update(Request $request)
    {
        $userId = in_array(Auth::user()->type, [3, 4, 5]) ? Auth::id() : $request->user_id;
        return User::find($userId)
            ->update($request->only([
                'name', 'phone', 'password', 'project_id',
                "image", "active", "phone"
            ]));
    }

    public function index(Request $request)
    {
        $users = User::with('project');
        if ($request->keyword) {
            $users->where('name', '%'.$request->keyword.'%');
        }
        $active = $request->active == 1 ? 1 :
            (($request->has('active') && $request->active == 0) ? 0 : 1);
        if ($request->active != 2) {
            $users = $users->where('active', $active);
        }
        return $users->paginate(18);
    }

    public function profile(Request $request)
    {
        $id = in_array(Auth::user()->type, [3, 4, 5]) ? Auth::id() : $request->user_id;
        return User::where('id', $id)
            ->with('project:id,name_en,name_ar,image')
            ->firstOrFail();
    }

    public function delete($id)
    {
        return User::find($id)
            ->delete();
    }

    /**
     * change user password
     *
     * @param  Request  $request
     * @return mixed
     */
    public function changePassword(Request $request)
    {
        $user = User::find($request->user_id);
        if (in_array(Auth::user()->type, [3, 4, 5])) {
            $user = Auth::user();
            $user->validateForPassportPasswordGrant($request->old_password);
        }
        return [
            'is_success' => $user->update([
                'password'        => $request->password,
                'pass_changed_at' => now()
            ])
        ];
    }

    /**
     * get current logged user
     * @return mixed
     */
    public function loggedUser()
    {
        $user = User::select(
            'id', 'name', 'email', 'username', 'phone', 'show_phone',
            'phone_verified_at', 'active', 'special', 'type', 'image',
            'created_at'
        )->where('id', Auth::id())
            ->first();

        if (!$user) {
            throw new BadRequestHttpException('برجاء تسجيل الدخول');
        }
        return $user;
    }
}
