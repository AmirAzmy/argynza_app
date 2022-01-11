<?php

namespace App\Models\Request;

use Illuminate\Database\Eloquent\Model;

class Errand extends Model
{
    protected $fillable = [
        'request_id', 'date', 'from', 'to'
    ];
}
