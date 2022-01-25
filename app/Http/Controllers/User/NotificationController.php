<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\NotificationResource;
use App\Http\Services\User\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class NotificationController extends Controller
{
    /**
     * @var NotificationService
     */
    private $service;

    /**
     * NotificationController constructor.
     * @param NotificationService $service
     */
    public function __construct(NotificationService $service)
    {
//        $this->middleware('auth:api');
        $this->service = $service;
    }

    /**
     * user notifications
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $service = $this->service->index($request);
        $service = new PaginationResource($service, NotificationResource::class);
        return Response::successResponse($service);
    }

    /**
     * mark all user notifications as read
     * @return mixed
     */
    public function markAllAsRead()
    {
        $service = $this->service->markAllAsRead();
        return Response::successResponse([['is_success' => $service]]);
    }

    /**
     * mark a notification as read
     * @param Request $request
     * @return mixed
     */
    public function markAsRead(Request $request)
    {
        $markAsRead = $this->service->markAsRead($request->id);
        $count      = $this->service->totalUnreadNotifications();
        return Response::successResponse(['is_success' => (int)$markAsRead, 'count' => $count]);
    }

    /**
     * count unread notification
     * @return mixed
     */
    public function totalUnreadNotifications()
    {
        $service = $this->service->totalUnreadNotifications();
        return Response::successResponse(['count' =>$service]);
    }

}
