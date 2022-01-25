<?php

namespace App\Models\Request;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class Reduction extends Model
{
    protected $fillable = [
        'date',
        'amount',
        'user_id',
        'request_id',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
