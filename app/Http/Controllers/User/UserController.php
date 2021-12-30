<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use App\Http\Services\User\UserServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserServices $userServices)
    {
        $this->userService = $userServices;
    }

    public function create(UserRequest $userRequest)
    {
        $service = $this->userService->create($userRequest);
        return Response::successResponse($service);
    }

    public function update(UserRequest $userRequest, $id)
    {
        $service = $this->userService->update($userRequest, $id);
        return Response::successResponse($service);
    }
}
