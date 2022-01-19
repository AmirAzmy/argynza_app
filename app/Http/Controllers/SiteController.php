<?php

namespace App\Http\Controllers;

use App\Helpers\CrudTrait;
use App\Http\Requests\SiteRequest;
use App\Models\Project\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SiteController extends Controller
{
    use CrudTrait;

    public function __construct(SiteRequest $request)
    {
        $this->request = $request;
        $this->model = new Site();
        $this->fields = ['name_ar', 'name_en', 'redius', 'lat', 'lng', 'project_id'];
    }

    public function index(Request $request)
    {
        $sites = Site::where('project_id', $request->project_id);
        if ($request->keyword) {
            $sites = $sites->where('name_ar', 'like', '%'.$request->keyword.'%')
                ->orWhere('name_en', 'like', '%'.$request->keyword.'%');
        }
        return Response::successResponse($sites->paginate(20));
    }
}
