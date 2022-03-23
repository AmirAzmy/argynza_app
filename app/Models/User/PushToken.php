<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class PushToken extends Model
{
    use SoftDeletes, Notifiable;

    protected $fillable=[
        'user_id',
        'push_token',
        'udid',
        'device_type',
        'device_name',
        'os_version',
        'app_version',
        'send_push'
    ];
}
