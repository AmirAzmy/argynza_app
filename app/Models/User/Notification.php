<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Notification extends Model
{
    protected $fillable = [
        'id',
        'notifiable_id',
        'notifiable_type',
        'data',
        'read_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($notification) {
            $notification->{$notification->getKeyName()} = (string)Str::uuid();
        });
    }


    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

    public function getDataAttribute($value)
    {
        return json_decode($value, true);
    }
}
