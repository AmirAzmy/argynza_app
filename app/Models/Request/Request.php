<?php

namespace App\Models\Request;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'notes', 'type', 'status', 'employee_id'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id')
            ->select('id', 'name', 'phone', 'type', 'image', 'active');
    }

    public function lateAndLeave()
    {
        return $this->hasOne(LateAndLeave::class)
            ->select('id', 'date', 'is_late', 'from', 'to', 'request_id');
    }

    public function loan()
    {
        return $this->hasOne(Loan::class)
            ->select('id', 'date', 'amount', 'request_id');
    }

    public function errand()
    {
        return $this->hasOne(Errand::class)
            ->select('id', 'request_id', 'date', 'from', 'to');
    }

    public function vacation()
    {
        return $this->hasOne(Vacation::class)
            ->select('id', 'start', 'end', 'request_id');
    }
}
