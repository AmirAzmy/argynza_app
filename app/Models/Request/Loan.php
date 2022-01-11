<?php

namespace App\Models\Request;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = ['date', 'amount', 'request_id'];
}
