<?php

namespace App\Models\Request;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Errand extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'request_id', 'date', 'from', 'to'
    ];
}
