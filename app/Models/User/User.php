<?php

namespace App\Models\User;

use App\Exceptions\NoPermissionException;
use App\Models\Attendance;
use App\Models\Project\Project;
use App\Models\Request\Request;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Lang;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class User extends Authenticatable
{
    use Notifiable, HasApiTokens;
    use SoftDeletes;

    private $types = [
        5 => ['en' => 'employee', 'ar' => 'موظف'],
        4 => ['en' => 'direct manger', 'ar' => 'مدير مباشر'],
        3 => ['en' => 'project manger', 'ar' => 'مدير المشروع'],
        2 => ['en' => 'HR', 'ar' => ' اتش ار'],
        1 => ['en' => 'general manger', 'ar' => 'مدير عام']
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'password', 'type', 'verification_code',
        'project_id', "image", "active", "job_title", "phone_verified_at"
    ];
    protected $appends = ['user_type'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //validations
    public function validateForPassportPasswordGrant($password)
    {
        if (!Hash::check($password, $this->password)) {
            throw new BadRequestHttpException(Lang::get('messages.invalid_username_or_password'));
        }
    }

    public function checkAccountVerification()
    {
        if (!$this->phone_verified_at) {
            throw new NoPermissionException(Lang::get('messages.unverified_error_msg'));
        }
    }

    public function checkActivation()
    {
        if (!$this->active) {
            throw new BadRequestHttpException(Lang::get('messages.deactivated_error_msg'));
        }
    }

    //accessors && mutators
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function setImageAttribute($value)
    {
        $this->attributes['image'] = (is_file($value)) ? $value->store('User') : null;
    }

    public function getImageAttribute($value)
    {
        return ($value) ? url('uploads/'.$value) : url('/user_avatar.png');
    }

    public function getUserTypeAttribute()
    {
        return ($this->types[$this->type][app()->getLocale()]) ?? 'employee';
    }

    public function setVerificationCodeAttribute($value)
    {

//        $this->attributes['verification_code'] = ($value !== null) ? rand(1000, 9999) : null;
        $this->attributes['verification_code'] = ($value !== null) ? 5555 : null;

    }

    //relations
    public function AauthAcessToken()
    {
        return $this->hasMany(OauthAccessToken::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class, 'employee_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function checkUserAttendance()
    {
        return (int) $this->attendances()
            ->where('day', now()->format('Y-m-d'))
            ->where('checkout', null)
            ->exists();
    }

    public function todayAttendance()
    {
        return $this->hasOne(Attendance::class, 'user_id')
            ->select('id', 'user_id', 'day', 'checkin', 'checkout', 'site_id');
    }
}
