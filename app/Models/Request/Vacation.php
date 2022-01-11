<?php

namespace App\Models\Request;

use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    protected $fillable = ['start', 'end', 'request_id'];
}
