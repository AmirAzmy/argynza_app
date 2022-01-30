<?php

namespace App\Models\Request;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LateAndLeave extends Model
{
    use SoftDeletes;

    protected $fillable = ['date', 'is_late', 'from', 'to', 'request_id'];
    protected $appends = [
        'type'
    ];

    public function getTypeAttribute()
    {
        return $this->is_late ? 'late' : 'leave';
    }

    public function getFromAttribute($value)
    {
        return Carbon::parse($value)->format('h:i A');
    }

    public function getToAttribute($value)
    {
        return Carbon::parse($value)->format('h:i A');
    }

}
