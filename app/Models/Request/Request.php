<?php

namespace App\Models\Request;

use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request extends Model
{
    use SoftDeletes;

    public $types = [
        'late_and_leave' => ['en' => 'Late and Leave', 'ar' => 'تأخير او مغادرة'],
        'vacation'       => ['en' => 'Vacation', 'ar' => 'اجازة'],
        'errand'         => ['en' => 'Errand', 'ar' => 'مأمورية'],
        'loan'           => ['en' => 'Loan', 'ar' => 'سلفه'],
        'reduction'      => ['en' => 'Reduction', 'ar' => 'خصم'],
    ];
    public $statusNames = ['pending' => ' قيد الإنتظار', 'approved' => 'موافق', 'rejected' => 'مرفوض'];

    protected $fillable = [
        'notes', 'type', 'status', 'employee_id', 'user_action_id', 'rejection_reason'
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
        return app()->getLocale() == 'ar' ? ($this->statusNames[$this->status] ?? 'NAN') : $this->status;
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

    public function reduction()
    {
        return $this->hasOne(Reduction::class)
            ->select('id', 'date', 'amount', 'user_id', 'request_id')
            ->with('employee:id,name,phone,type');
    }
}
