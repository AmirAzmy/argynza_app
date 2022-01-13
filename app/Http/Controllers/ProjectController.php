<?php

namespace App\Http\Controllers;

use App\Helpers\BaseTrait;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\ProjectResource;
use App\Http\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ProjectController extends Controller
{
    use BaseTrait;

    public function __construct(ProjectService $projectService, ProjectRequest $request)
    {
        $this->service = $projectService;
    }

    public function get($id)
    {
        $service = $this->service->get($id);
        $service = new ProjectResource($service);
        return Response::successResponse($service);
    }

}
