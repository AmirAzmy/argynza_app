<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestRequest;
use App\Http\Services\Request\RequestService;
use Illuminate\Support\Facades\Response;

class RequestController extends Controller
{
    private $service;

    public function __construct(RequestService $requestService)
    {
        $this->middleware('auth:api');
        $this->service = $requestService;
    }

    public function create(RequestRequest $request)
    {
        $service = $this->service->create($request);
        return Response::successResponse($service);
    }

    public function get($id)
    {
        $service = $this->service->get($id);
        return Response::successResponse($service);
    }
}