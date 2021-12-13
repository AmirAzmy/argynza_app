<?php

namespace App\Models\User;

use App\Exceptions\NoPermissionException;
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

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'password', 'verification_code',
        "image", "active", "phone", "phone_verified_at"
    ];

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
}
