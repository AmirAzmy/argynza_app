<?php

namespace App\Models\Request;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request extends Model
{
    use SoftDeletes;

    public $types = [
        'late_and_leave' => ['en' => 'Late and Leave', 'ar' => 'تأخير او مغادرة'],
        'vacation'       => ['en' => 'Vacation', 'ar' => 'اجازة'],
        'errand'         => ['en' => 'Errand', 'ar' => 'مأمورية'],
        'loan'           => ['en' => 'Loan', 'ar' => 'سلفه']
    ];
    public $statusNames = ['pending' => ' في الإنتظار', 'approved' => 'موافق', 'rejected' => 'مرفوض'];

    protected $fillable = [
        'notes', 'type', 'status', 'employee_id'
    ];
    protected $appends = [
        'status_name', 'type_name'
    ];

    public function getTypeNameAttribute()
    {
        return ($this->types[$this->type][app()->getLocale()]) ?? 'NAN';
    }

    public function getStatusNameAttribute()
    {
        return app()->getLocale() == 'ar' ? $this->statusNames[$this->status] : $this->status;
    }

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
