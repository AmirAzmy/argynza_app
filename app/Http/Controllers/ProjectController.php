<?php

namespace App\Http\Controllers;

use App\Helpers\BaseTrait;
use App\Helpers\CrudTrait;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\ProjectResource;
use App\Http\Services\ProjectService;
use App\Models\Project\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ProjectController extends Controller
{
    use CrudTrait;

    public function __construct(ProjectRequest $request)
    {
        $this->request = $request;
        $this->relations = ['sites:id,name_en,name_ar,redius,lat,lng,project_id'];
        $this->model = new Project();
        $this->fields = ['name_ar', 'name_en', 'image'];
    }

    public function get($id)
    {
        $project = Project::select('id', 'name_ar', 'name_en', 'image')
            ->where('id', $id)
            ->with('sites:id,name_en,name_ar,redius,lat,lng,project_id')
            ->firstOrFail();
        return Response::successResponse(new ProjectResource($project));
    }


    public function index(Request $request)
    {
        $projects = Project::withCount('sites')
            ->orderBy('id', 'desc');
        if ($request->keyword) {
            $projects->where('name_ar', 'like', '%'.$request->keyword.'%')
                ->orWhere('name_en', 'like', '%'.$request->keyword.'%');
        }
        if ($request->return_all) {
            return Response::successResponse(ProjectResource::collection($projects->get()));
        }
        return Response::successResponse($projects->paginate(20));
    }

}
