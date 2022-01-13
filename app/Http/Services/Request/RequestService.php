<?php

namespace App\Http\Services\Request;

use Illuminate\Http\Request;
use App\Models\Request\Request as EmpRequest;
use Illuminate\Support\Facades\Auth;

class RequestService
{
    public function create(Request $request)
    {
        $request->merge([
            'employee_id' => Auth::id()
        ]);
        $empRequest = EmpRequest::create($request->only([
            'notes', 'employee_id', 'status', 'type'
        ]));
        $type = $request->type == 'late_and_leave' ? 'lateAndLeave' : $request->type;
        $empRequest->$type()->create($request->all());

        return $empRequest;
    }

    public function get($id)
    {
        return EmpRequest::where('id', $id)
            ->with('lateAndLeave', 'loan', 'errand', 'vacation')
            ->firstOrFail();
    }

    public function index(Request $request)
    {
        $empRequests = EmpRequest::select('id', 'notes', 'type', 'status', 'employee_id')
            ->with('employee', 'lateAndLeave', 'loan', 'errand', 'vacation');
        if ($request->status) {
            $empRequests = $empRequests->where('status', $request->status);
        }
        if (!in_array(Auth::user()->type, [1, 2])) {
            $empRequests = $empRequests->where('employee_id', Auth::id());
        }
        return $empRequests->paginate(15);
    }
}
