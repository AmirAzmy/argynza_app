<?php

namespace App\Http\Services\Request;

use App\Exceptions\NoPermissionException;
use Illuminate\Http\Request;
use App\Models\Request\Request as EmpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;

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
        $empRequest = EmpRequest::where('id', $id)
            ->with('lateAndLeave', 'loan', 'errand', 'vacation')
            ->firstOrFail();
        if ($empRequest->employee_id != Auth::id() && !in_array(Auth::user()->type, [1, 2])) {
            throw new NoPermissionException(Lang::get('messages.no_permissions_msg'));
        }
        return $empRequest;
    }

    public function changeStatus($request, $id)
    {
        $empRequest = EmpRequest::where('id', $id)
            ->firstOrFail();
        return ['is_success' => $empRequest->update(['status' => $request->status])];
    }

    public function index(Request $request)
    {
        $empRequests = EmpRequest::select('id', 'notes', 'type', 'status', 'employee_id','created_at')
            ->with('employee', 'lateAndLeave', 'loan', 'errand', 'vacation');
        if ($request->date) {
            $empRequests = $empRequests->whereDate('created_at', $request->date);
        }
        if ($request->status) {
            $empRequests = $empRequests->where('status', $request->status);
        }
        if (!in_array(Auth::user()->type, [1, 2])) {
            $empRequests = $empRequests->where('employee_id', Auth::id());
        }
        return $empRequests->paginate(15);
    }
}
