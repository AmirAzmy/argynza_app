<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use App\Http\Services\User\UserServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    private $service;

    public function __construct(UserServices $service)
    {
        $this->middleware('auth:api');
        $this->middleware('check.role:1,2')
            ->except(['profile', 'changePassword']);
        $this->service = $service;
    }

    /**
     * create new user
     * @param  UserRequest  $request
     * @return mixed
     */
    public function create(UserRequest $request)
    {
        $service = $this->service->create($request);
        return Response::successResponse($service);
    }

    /**
     * update user data
     * @param  UserRequest  $request
     * @return mixed
     */
    public function update(UserRequest $request, $id)
    {
        $service = $this->service->update($request, $id);
        return Response::successResponse($service);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete($id)
    {
//        $this->authorize('adminOnly');
        $service = $this->service->delete($id);
        if (!$service) {
            return Response::errorResponse($service);
        }
        return Response::successResponse($service);
    }

    /**
     * return users
     * @param  UserRequest  $request
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(UserRequest $request)
    {
//        $this->authorize('adminOnly');
        $service = $this->service->index($request);
        return Response::successResponse($service);
    }

    /**
     * user profile
     * @return mixed
     */
    public function profile(Request $request)
    {
        $service = $this->service->profile($request);
        return Response::successResponse($service);
    }

    /**
     * change user Password
     * @param  UserRequest  $request
     * @return mixed
     */
    public function changePassword(UserRequest $request)
    {
        $service = $this->service->changePassword($request);
        return Response::successResponse($service);
    }

    /**
     * activate or deactivate user
     * @param $id
     * @return mixed
     */
    public function userActivation($id)
    {
        $service = $this->service->userActivation($id);
        return Response::successResponse($service);
    }

    /**
     * get current logged user
     * @return mixed
     */
    public function loggedUser()
    {
        $result = $this->service->loggedUser();
        return Response::successResponse($result);
    }
}
