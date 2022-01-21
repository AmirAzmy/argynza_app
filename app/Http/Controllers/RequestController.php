<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestRequest;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\RequestResource;
use App\Http\Services\Request\RequestService;
use Illuminate\Support\Facades\Response;

class RequestController extends Controller
{
    private $service;

    public function __construct(RequestService $requestService)
    {
        $this->middleware('auth:api');

        $this->middleware('check.role:1,2')
            ->only(['changeStatus']);
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

    public function changeStatus(RequestRequest $request, $id)
    {
        $service = $this->service->changeStatus($request, $id);
        return Response::successResponse($service);
    }

    public function index(RequestRequest $request)
    {
        $service = $this->service->index($request);
        $service = new PaginationResource($service, RequestResource::class);
        return Response::successResponse($service);
    }

}
