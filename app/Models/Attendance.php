<?php

namespace App\Models;

use App\Models\Project\Site;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id', 'day', 'checkin', 'checkout', 'site_id'
    ];

    public function site()
    {
        return $this->belongsTo(Site::class)
            ->select('id', 'name_'.app()->getLocale().' as name');
    }
}
