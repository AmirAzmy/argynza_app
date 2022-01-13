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
            'name_ar','name_en', 'image'
        ]));
        return $project;
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id)
            ->update($request->only([
                'name_ar', 'name_en', 'image'
            ]));
        return $project;
    }


    public function delete($id)
    {
        return Project::findOrFail($id)
            ->delete();
    }
}
