<?php

namespace App\Http\Services;

use App\Models\Project\Project;
use App\Models\Project\Site;
use App\Models\Request\Request;
use App\Models\User\User;

class HomeService
{
    public function dashboard()
    {
        return [
            "employees_count" => User::count(),
            "projects_count"  => Project::count(),
            "requests_count"  => Request::count(),
            "sites_count"     => Site::count(),
        ];
    }
}
