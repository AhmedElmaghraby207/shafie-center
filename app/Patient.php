<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'password',
        'phone',
        'phone_verified_at',
        'is_active',
        'image',
        'age',
        'weight',
        'height',
        'gender',
        'address',
        'facebook_id',
        'google_id',
        'apple_id',
        'mobile_os',
        'mobile_model'
    ];
}
