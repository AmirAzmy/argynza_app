<?php

namespace App\Http\Services;

use App\Models\Project\Project;
use App\Models\Project\Site;
use Illuminate\Http\Request;

class ProjectService
{
    public function create(Request $request)
    {
        $project = Project::create($request->only([
            'name', 'image'
        ]));
        $project->sites()->createMany($request->sites);
        return $project;
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id)
            ->update($request->only([
                'name', 'image'
            ]));
        foreach ($request->sites as $site) {
            Site::updateOrCreate([
                'id' => ($site['id']) ?? null,
            ], [
                'name'       => $site['name'], 'redius' => $site['redius'],
                'lat'        => $site['lat'], 'lng' => $site['lng'],
                'project_id' => $site['project_id']
            ]);
        }
        return $project;
    }

    public function get($id)
    {
        return Project::select('id', 'name_ar', 'name_en', 'image')
            ->where('id', $id)
            ->with('sites:id,name_en,name_ar,redius,lat,lng,project_id')
            ->firstOrFail();
    }

    public function delete($id)
    {
        return Project::findOrFail($id)
            ->delete();
    }

    public function index(Request $request)
    {
        $projects = Project::withCount('sites');
        if ($request->keyword) {
            $projects->where('name_ar', 'like', '%'.$request->keyword.'%')
                ->orWhere('name_en', 'like', '%'.$request->keyword.'%');
        }
        if ($request->return_all) {
            return $projects->get();
        }
        return $projects->paginate(20);
    }
}
