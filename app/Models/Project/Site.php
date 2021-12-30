<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $fillable = [
        'name_ar', 'name_en', 'redius', 'lat', 'lng', 'project_id'
    ];
}
