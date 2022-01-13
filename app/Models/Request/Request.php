<?php

namespace App\Models\Request;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'notes', 'type', 'status', 'employee_id'
    ];

    public function lateAndLeave()
    {
        return $this->hasOne(LateAndLeave::class);
    }

    public function loan()
    {
        return $this->hasOne(Loan::class);
    }

    public function errand()
    {
        return $this->hasOne(Errand::class);
    }

    public function vacation()
    {
        return $this->hasOne(Vacation::class);
    }
}
