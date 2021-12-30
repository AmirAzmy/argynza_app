<?php

namespace App\Http\Services\User;

use App\Models\User\User;
use Illuminate\Http\Request;

class UserServices
{
    public function create(Request $request)
    {
        return User::create($request->only([
            'name', 'phone', 'password', 'project_id',
            "image", "active", "phone"
        ]));
    }

    public function update(Request $request, $id)
    {
        return User::find($id)
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
        return $users->paginate(18);
    }

    public function get($id)
    {
        return User::where('id', $id)
            ->with('project')
            ->first();
    }

    public function delete($id)
    {
        return User::find($id)
            ->delete();
    }
}
