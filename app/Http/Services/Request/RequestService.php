<?php

namespace App\Http\Services\Request;

use App\Exceptions\NoPermissionException;
use App\Notifications\DBNotification;
use Illuminate\Http\Request;
use App\Models\Request\Request as EmpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Notification as NotificationQueue;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RequestService
{
    public function create(Request $request)
    {
        $request->merge([
            'employee_id' => Auth::id()
        ]);
        if (Auth::user()->type == 5 && $request->type == 'reduction') {
            throw new BadRequestHttpException(Lang::get('messages.not_allow_msg'));
        }
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
        NotificationQueue::send($empRequest->employee()->first(), new DBNotification([
            "title_en"   => $empRequest->status,
            "title_ar"   => $empRequest->statusNames[$empRequest->status],
            "body_en"    => $empRequest->types[$empRequest->type]['en'],
            "body_ar"    => $empRequest->types[$empRequest->type]['ar'],
            'image'      => '',
            'actionType' => 'request',
            'actionId'   => $empRequest->id,
        ]));
        return ['is_success' => $empRequest->update(['status' => $request->status])];
    }

    public function index(Request $request)
    {
        $empRequests = EmpRequest::select('id', 'notes', 'type', 'status'
            , 'rejection_reason', 'employee_id', 'created_at')
            ->with('employee', 'lateAndLeave', 'loan', 'errand', 'vacation', 'reduction');
        if ($request->date) {
            $empRequests = $empRequests->whereDate('created_at', $request->date);
        }
        if ($request->status) {
            $empRequests = $empRequests->where('status', $request->status);
        }
        if ($request->type) {
            $empRequests = $empRequests->where('type', $request->type);
        }
        if ($request->keyword) {
            $empRequests = $empRequests->whereHas('employee', function ($employee) use ($request) {
                $employee->where('name', 'like', '%'.$request->keyword.'%')
                    ->orWhere('phone', 'like', '%'.$request->keyword.'%');
            });
        }
        if (!in_array(Auth::user()->type, [1, 2])) {
            $empRequests = $empRequests->where('employee_id', Auth::id());
        }
        return $empRequests->paginate(15);
    }

}
