<?php


namespace App\Http\Services\User;

use App\Models\User\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    public function index($request)
    {
        $paginationNum = $request->reduce_pagination ? 5 : 15;
        return Notification::select('id', 'notifiable_id', 'notifiable_type', 'data', 'read_at', 'created_at')
//            ->where('notifiable_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate($paginationNum);
    }

    public function markAsRead($id)
    {
        $data = Notification::where('id', $id)
            ->where('notifiable_id', Auth::id())
            ->firstOrFail();
        return $data->update([
            'read_at' => now()
        ]);
    }

    public function markAllAsRead()
    {
        return Notification::where('notifiable_id', Auth::id())
            ->update([
                'read_at' => now()
            ]);
    }

    public function totalUnreadNotifications()
    {
        return Notification::where('notifiable_id', Auth::id())
            ->where('read_at', null)
            ->count();
    }

}
