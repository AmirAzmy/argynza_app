<?php

namespace App\Http\Services\User;

use App\Models\Attendance;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserServices
{
    public function create(Request $request)
    {
        $request->merge(['verification_code' => 0]);
        return User::create($request->only([
            'name', 'phone', 'password', 'type', 'verification_code',
            'project_id', "image", "active", "job_title",
        ]));
    }

    public function update(Request $request, $id)
    {
        $userId = in_array(Auth::user()->type, [3, 4, 5]) ? Auth::id() : $id;
        return User::find($userId)
            ->update($request->only([
                'name', 'phone', 'password', 'type',
                'project_id', "image", "active", "job_title"
            ]));
    }

    public function index(Request $request)
    {
        $users = User::with('project')
            ->orderBy('id', 'desc');
        if ($request->keyword) {
            $users->where('name', 'like', '%'.$request->keyword.'%');
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
        $user = User::where('id', $id)
            ->with('project:id,name_en,name_ar,image')
            ->withCount('requests')
            ->firstOrFail();
        $user['is_attended'] = $user->checkUserAttendance();
        return $user;
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
            'id', 'name', 'phone', 'verification_code',
            'project_id', "image", "active", "phone", "phone_verified_at"
        )->where('id', Auth::id())
            ->first();

        if (!$user) {
            throw new BadRequestHttpException('برجاء تسجيل الدخول');
        }
        return $user;
    }

    public function employeeAttendance(Request $request)
    {
        Attendance::where('user_id', Auth::id())
            ->where('day', now()->format('Y-m-d'));
        $attendance = Attendance::create();
    }

}
