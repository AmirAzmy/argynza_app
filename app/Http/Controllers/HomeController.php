<?php

namespace App\Http\Controllers;

use App\Http\Services\HomeService;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    private $service;

    public function __construct(HomeService $homeService)
    {
        $this->service = $homeService;
    }

    public function dashboard()
    {
        $service = $this->service->dashboard();
        return Response::successResponse($service);
    }
}
