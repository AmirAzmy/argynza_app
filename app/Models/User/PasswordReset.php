<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'phone', 'token'
    ];

    public function setTokenAttribute($value)
    {
        $this->attributes['created_at'] = now();
        $this->attributes['token'] = $value;
    }
}
