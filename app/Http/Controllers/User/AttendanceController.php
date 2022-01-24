<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AttendanceRequest;
use App\Http\Services\User\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AttendanceController extends Controller
{
    private $service;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->middleware('auth:api');
        $this->service = $attendanceService;
    }

    public function checkInAndOut(AttendanceRequest $request)
    {
        $service = $this->service->checkInAndOut($request);
        return Response::successResponse($service);
    }

    public function checkLocation(AttendanceRequest $request)
    {
        $service = $this->service->checkLocation($request);
        return Response::successResponse($service);
    }
}
