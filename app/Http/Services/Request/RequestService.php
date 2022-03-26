<?php

namespace App\Http\Services\Request;

use App\Exceptions\NoPermissionException;
use App\Models\User\PushToken;
use App\Models\User\User;
use App\Notifications\DBNotification;
use App\ThirdParty\AndroidNotification;
use App\ThirdParty\WebNotification;
use Illuminate\Http\Request;
use App\Models\Request\Request as EmpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Notification as NotificationQueue;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RequestService
{
    use AndroidNotification;

    public function create(Request $request)
    {
        $request->merge([
            'employee_id' => Auth::id(),
            'status'      => 'pending',
        ]);
        if (Auth::user()->type == 5 && $request->type == 'reduction') {
            throw new BadRequestHttpException(Lang::get('messages.not_allow_msg'));
        }
        $empRequest = EmpRequest::create($request->only([
            'notes', 'employee_id', 'status', 'type'
        ]));
        $type = $request->type == 'late_and_leave' ? 'lateAndLeave' : $request->type;
        $empRequest->$type()->create($request->all());
        if ($type != 'reduction') {
            $this->prepareAndSendNotification($empRequest);
        }
        return $empRequest;
    }

    private function prepareAndSendNotification($empRequest)
    {
        $admins = User::where('type', 2)->get();
        $userIds = count($admins) ? $admins->pluck('id')->toArray() : [];
        $pushTokens = PushToken::whereIn('user_id', $userIds)
            ->get();
        $devices = $pushTokens->pluck('push_token')->unique()->toArray();
//        dump($devices, $userIds);
        $this->setDevices($devices);
        $this->setPayload([]);
        $this->prepareMessageContent(' طلب جديد من الموظف ',
            '  طلب جديد لموظف نوعه '.$empRequest->types[$empRequest->type]['ar']);
        $this->sendPushMessage();
        $this->storePushNotification($empRequest, $admins);
    }

    public function get($id)
    {
        $empRequest = EmpRequest::where('id', $id)
            ->with('employee', 'lateAndLeave', 'loan', 'errand', 'vacation', 'reduction')
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
        $this->storePushNotification($empRequest, $empRequest->employee()->first());
        return [
            'is_success' => $empRequest->update([
                'user_action_id'   => Auth::id(),
                'status'           => $request->status,
                'rejection_reason' => $request->rejection_reason
            ])
        ];
    }

    private function storePushNotification($empRequest, $user)
    {
        NotificationQueue::send($user, new DBNotification([
            "title_en"   => $empRequest->status,
            "title_ar"   => $empRequest->statusNames[$empRequest->status],
            "body_en"    => $empRequest->types[$empRequest->type]['en'],
            "body_ar"    => $empRequest->types[$empRequest->type]['ar'],
            'image'      => '',
            'actionType' => 'request',
            'actionId'   => $empRequest->id,
        ]));

    }

    public function index(Request $request)
    {
        $empRequests = EmpRequest::select(
            'id', 'notes', 'type', 'status', 'rejection_reason',
            'employee_id', 'created_at'
        )
            ->with(
                'employee', 'lateAndLeave', 'loan', 'errand',
                'vacation', 'reduction'
            )->orderBy('id', 'desc');
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
